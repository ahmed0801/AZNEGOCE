<?php

namespace App\Services;

use App\Models\DiscountGroup;
use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\PurchaseProject;
use App\Models\PurchaseProjectLine;
use App\Models\PurchaseNote;
use App\Models\PurchaseNoteLine;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnLine;
use App\Models\Reception;
use App\Models\ReceptionLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TvaGroup;
use Illuminate\Support\Facades\DB;

/**
 * Cœur métier des achats.
 *
 * Règles (décidées avec le client) :
 *  - le STOCK entre à la RÉCEPTION, jamais à la validation d'une commande ;
 *  - le COÛT de l'article = dernier prix d'achat (pas de coût moyen) ;
 *  - le PRIX DE VENTE d'un article existant n'est JAMAIS modifié par un achat ;
 *  - tout est fait dans une transaction : si une ligne est refusée, rien n'est enregistré,
 *    pas même le numéro de document.
 */
class PurchaseDocumentService
{
    /* ═══════════════════════════ NUMÉROTATION ═══════════════════════════ */

    /**
     * Prochain numéro de la souche. À appeler DANS une transaction : la ligne de souche est
     * verrouillée, deux utilisateurs ne peuvent donc jamais obtenir le même numéro.
     * Format identique au reste de l'application : préfixe . suffixe . numéro.
     */
    public function nextNumber(string $type): string
    {
        $souche = Souche::where('type', $type)->lockForUpdate()->first();
        if (!$souche) {
            throw new PurchaseException("Souche « $type » manquante : créez-la dans Paramètres › Souches.");
        }
        $next = (int) $souche->last_number + 1;
        $souche->last_number = $next;
        $souche->save();

        return ($souche->prefix ?? '') . ($souche->suffix ?? '') . str_pad((string) $next, (int) $souche->number_length, '0', STR_PAD_LEFT);
    }

    /* ═══════════════════════════ LIGNES & TOTAUX ═══════════════════════════ */

    private function num($v): float
    {
        if ($v === null || $v === '') return 0.0;
        return (float) str_replace([' ', ','], ['', '.'], (string) $v);
    }

    /**
     * Prix proposé pour un article chez un fournisseur : son emplacement s'il le référence, sinon le principal.
     * @return array{0:float,1:float,2:bool}  prix brut, remise, « vient-il de CE fournisseur ? »
     */
    public function costFor(Item $item, ?Supplier $supplier): array
    {
        if ($supplier) {
            foreach ([['codefournisseur', 'cost_price', 'remise_achat'], ['codefournisseur_2', 'cost_price_2', 'remise_achat_2'], ['codefournisseur_3', 'cost_price_3', 'remise_achat_3']] as [$c, $p, $r]) {
                if ($item->{$c} && $item->{$c} === $supplier->code) {
                    return [(float) $item->{$p}, (float) $item->{$r}, true];
                }
            }
        }
        return [(float) $item->cost_price, (float) $item->remise_achat, !$supplier || !$item->codefournisseur];
    }

    /** TVA par défaut d'une ligne : fournisseur exonéré => 0 ; sinon taux de l'article, sinon taux du fournisseur. */
    public function defaultTva(Supplier $supplier, ?Item $item): float
    {
        $supplierRate = (float) ($supplier->tvaGroup->rate ?? 0);
        if ($supplierRate <= 0) return 0.0;
        $itemRate = $item && $item->tvaGroup ? $item->tvaGroup->rate : null;
        return $itemRate !== null ? (float) $itemRate : $supplierRate;
    }

    /**
     * Valide et normalise les lignes postées. Crée les articles « nouveaux » saisis dans le document.
     *
     * @return array<int,array>  lignes prêtes à enregistrer
     */
    public function prepareLines(Supplier $supplier, array $rawLines): array
    {
        $rawLines = array_values(array_filter($rawLines, 'is_array'));
        if (!$rawLines) {
            throw new PurchaseException('Ajoutez au moins une ligne au document.');
        }

        $codes = [];
        foreach ($rawLines as $l) {
            $c = trim((string) ($l['article_code'] ?? ''));
            if ($c !== '') $codes[] = $c;
        }
        $byCode = [];
        foreach (Item::with('tvaGroup')->whereIn('code', array_unique($codes))->get() as $it) {
            $byCode[mb_strtolower($it->code)] = $it;
        }

        $supplier->loadMissing('tvaGroup');
        $out = [];
        foreach ($rawLines as $i => $l) {
            $n    = $i + 1;
            $code = trim((string) ($l['article_code'] ?? ''));
            if ($code === '') throw new PurchaseException("Ligne $n : la référence est obligatoire.");

            $qty    = $this->num($l['ordered_quantity'] ?? $l['quantity'] ?? $l['received_quantity'] ?? 0);
            $pu     = $this->num($l['unit_price_ht'] ?? 0);
            $remise = $this->num($l['remise'] ?? 0);

            if ($qty <= 0)                          throw new PurchaseException("Ligne $n ($code) : la quantité doit être supérieure à 0.");
            if (abs($qty - round($qty)) > 0.00001)  throw new PurchaseException("Ligne $n ($code) : la quantité doit être un nombre entier.");
            if ($pu < 0)                            throw new PurchaseException("Ligne $n ($code) : le prix ne peut pas être négatif.");
            if ($remise < 0 || $remise > 100)       throw new PurchaseException("Ligne $n ($code) : la remise doit être comprise entre 0 et 100 %.");
            $qty = (int) round($qty);

            $isNew = !empty($l['is_new_item']);
            $item  = $byCode[mb_strtolower($code)] ?? null;

            if ($isNew) {
                $name = trim((string) ($l['item_name'] ?? ''));
                if ($name === '') throw new PurchaseException("Ligne $n ($code) : la désignation du nouvel article est obligatoire.");
                if ($item) {
                    throw new PurchaseException("Ligne $n : la référence « {$item->code} » existe déjà ({$item->name}). Sélectionnez-la dans la recherche au lieu de la recréer.");
                }
                $tva  = isset($l['tva']) && $l['tva'] !== '' ? $this->num($l['tva']) : $this->defaultTva($supplier, null);
                $item = $this->createItem($supplier, $code, $name, $pu, $remise, $this->num($l['sale_price'] ?? 0), $tva);
                $byCode[mb_strtolower($code)] = $item;
            } elseif (!$item) {
                throw new PurchaseException("Ligne $n : l'article « $code » n'existe pas.");
            }

            $tva = isset($l['tva']) && $l['tva'] !== '' ? $this->num($l['tva']) : $this->defaultTva($supplier, $item);
            if ($tva < 0 || $tva > 100) throw new PurchaseException("Ligne $n ($code) : la TVA doit être comprise entre 0 et 100 %.");

            $netUnit = $pu * (1 - $remise / 100);
            $totalHt = round($qty * $netUnit, 2);

            $out[] = [
                'item'          => $item,
                'code'          => $item->code,
                'qty'           => $qty,
                'pu'            => round($pu, 2),
                'remise'        => round($remise, 2),
                'tva'           => round($tva, 2),
                'net_unit'      => $netUnit,
                'total_ht'      => $totalHt,
                'total_ttc'     => round($totalHt * (1 + $tva / 100), 2),
                'is_new'        => $isNew,
                'order_line_id' => !empty($l['order_line_id']) ? (int) $l['order_line_id'] : null,
            ];
        }
        return $out;
    }

    /** Totaux du document + taux de TVA « dominant » (celui qui pèse le plus, stocké sur l'en-tête). */
    public function totals(array $lines): array
    {
        $ht = 0.0; $ttc = 0.0; $byRate = [];
        foreach ($lines as $l) {
            $ht  += $l['total_ht'];
            $ttc += $l['total_ttc'];
            $k = (string) $l['tva'];
            $byRate[$k] = ($byRate[$k] ?? 0) + $l['total_ht'];
        }
        $dominant = 0.0;
        if ($byRate) { arsort($byRate); $dominant = (float) array_key_first($byRate); }
        return ['ht' => round($ht, 2), 'ttc' => round($ttc, 2), 'tva_rate' => $dominant, 'by_rate' => $byRate];
    }

    private function createItem(Supplier $supplier, string $code, string $name, float $pu, float $remise, float $salePrice, float $tva): Item
    {
        $tvaGroupId = TvaGroup::where('rate', $tva)->orderBy('id')->value('id');
        return Item::create([
            'code'              => $code,
            'name'              => $name,
            'cost_price'        => round($pu, 2),
            'remise_achat'      => round($remise, 2),
            'sale_price'        => round($salePrice, 2),
            'codefournisseur'   => $supplier->code,
            'tva_group_id'      => $tvaGroupId,
            'discount_group_id' => DiscountGroup::orderBy('id')->value('id'),
            'is_active'         => 1,
        ]);
    }

    /* ═══════════════════════════ COÛT D'ACHAT ═══════════════════════════ */

    /**
     * Le coût de l'article devient le dernier prix d'achat (brut + remise du fournisseur).
     * Les 3 emplacements fournisseur de l'article fonctionnent comme une liste « récents » :
     * le fournisseur qui vient de livrer passe en premier, les autres descendent d'un cran.
     * Le prix de VENTE n'est jamais touché.
     */
    public function applyPurchaseCost(Item $item, Supplier $supplier, float $gross, float $remise): void
    {
        $slots = [
            ['codefournisseur',   'cost_price',   'remise_achat'],
            ['codefournisseur_2', 'cost_price_2', 'remise_achat_2'],
            ['codefournisseur_3', 'cost_price_3', 'remise_achat_3'],
        ];

        $entries = [];
        foreach ($slots as [$c, $p, $r]) {
            $code = $item->{$c};
            if ($code !== null && $code !== '' && $code !== $supplier->code) {
                $entries[] = [$code, (float) $item->{$p}, (float) $item->{$r}];
            }
        }
        array_unshift($entries, [$supplier->code, round($gross, 2), round($remise, 2)]);
        $entries = array_slice($entries, 0, 3);

        foreach ($slots as $k => [$c, $p, $r]) {
            if (isset($entries[$k])) {
                [$item->{$c}, $item->{$p}, $item->{$r}] = $entries[$k];
            } else {
                $item->{$c} = null; $item->{$p} = 0; $item->{$r} = 0;
            }
        }
        $item->save();
    }

    /* ═══════════════════════════ PROJET ═══════════════════════════ */

    public function createProject(Supplier $supplier, array $header, array $rawLines): PurchaseProject
    {
        return DB::transaction(function () use ($supplier, $header, $rawLines) {
            $lines  = $this->prepareLines($supplier, $rawLines);
            $totals = $this->totals($lines);

            $project = PurchaseProject::create([
                'numdoc'      => $this->nextNumber('projet_commande'),
                'supplier_id' => $supplier->id,
                'order_date'  => $header['date'] ?? now()->toDateString(),
                'notes'       => $header['notes'] ?? null,
                'total_ht'    => $totals['ht'],
                'total_ttc'   => $totals['ttc'],
                'tva_rate'    => $totals['tva_rate'],
                'status'      => 'brouillon',
            ]);

            foreach ($lines as $l) {
                PurchaseProjectLine::create([
                    'purchase_project_id' => $project->id,
                    'article_code'        => $l['code'],
                    'ordered_quantity'    => $l['qty'],
                    'unit_price_ht'       => $l['pu'],
                    'remise'              => $l['remise'],
                    'tva'                 => $l['tva'],
                    'total_ligne_ht'      => $l['total_ht'],
                    'prix_ttc'            => $l['total_ttc'],
                ]);
            }
            return $project->load('lines');
        });
    }

    /**
     * Modification d'un projet (brouillon ou validé, jamais converti) : lignes remplacées, totaux recalculés,
     * TVA par ligne. $action : 'save' (reste en l'état/brouillon), 'validate' (→ validée), 'convert' (→ commande).
     *
     * @return array{project:PurchaseProject,order:?PurchaseOrder}
     */
    public function updateProject(PurchaseProject $project, Supplier $supplier, array $header, array $rawLines, string $action = 'save'): array
    {
        return DB::transaction(function () use ($project, $supplier, $header, $rawLines, $action) {
            $project = PurchaseProject::whereKey($project->id)->lockForUpdate()->firstOrFail();
            if ($project->status === 'converted') throw new PurchaseException("Le projet {$project->numdoc} est déjà converti en commande : il ne se modifie plus.");

            $lines  = $this->prepareLines($supplier, $rawLines);
            $totals = $this->totals($lines);

            PurchaseProjectLine::where('purchase_project_id', $project->id)->delete();
            foreach ($lines as $l) {
                PurchaseProjectLine::create([
                    'purchase_project_id' => $project->id, 'article_code' => $l['code'], 'ordered_quantity' => $l['qty'],
                    'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'tva' => $l['tva'],
                    'total_ligne_ht' => $l['total_ht'], 'prix_ttc' => $l['total_ttc'],
                ]);
            }
            $project->update([
                'supplier_id' => $supplier->id, 'order_date' => $header['date'] ?? $project->order_date, 'notes' => $header['notes'] ?? null,
                'total_ht' => $totals['ht'], 'total_ttc' => $totals['ttc'], 'tva_rate' => $totals['tva_rate'],
                'status' => $action === 'validate' ? 'validée' : $project->status,
            ]);

            $order = null;
            if ($action === 'convert') $order = $this->convertProject($project->id, $header);
            return ['project' => $project->fresh('lines'), 'order' => $order];
        });
    }

    public function validateProject(int $projectId): PurchaseProject
    {
        return DB::transaction(function () use ($projectId) {
            $p = PurchaseProject::whereKey($projectId)->lockForUpdate()->firstOrFail();
            if ($p->status === 'converted') throw new PurchaseException("Le projet {$p->numdoc} est déjà converti en commande.");
            if ($p->status === 'validée') throw new PurchaseException("Le projet {$p->numdoc} est déjà validé.");
            $p->update(['status' => 'validée']);
            return $p;
        });
    }

    /**
     * Projet → commande fournisseur (validée par défaut : la décision d'achat est prise). Un projet ne se
     * convertit qu'une fois ; le numéro de commande est tiré sous verrou comme tous les autres documents.
     */
    public function convertProject(int $projectId, array $header = []): PurchaseOrder
    {
        return DB::transaction(function () use ($projectId, $header) {
            $p = PurchaseProject::with('lines')->whereKey($projectId)->lockForUpdate()->firstOrFail();
            if ($p->status === 'converted') throw new PurchaseException("Le projet {$p->numdoc} a déjà été converti en commande.");
            if ($p->lines->isEmpty()) throw new PurchaseException("Le projet {$p->numdoc} n'a aucune ligne.");
            $supplier = Supplier::with('tvaGroup')->findOrFail($p->supplier_id);

            $raw = $p->lines->map(function ($l) {
                return ['article_code' => $l->article_code, 'ordered_quantity' => $l->ordered_quantity, 'unit_price_ht' => $l->unit_price_ht, 'remise' => $l->remise, 'tva' => $l->tva];
            })->all();

            return $this->createOrder($supplier, [
                'date'     => $header['order_date_override'] ?? now()->toDateString(),
                'notes'    => $p->notes,
                'status'   => $header['status'] ?? 'validée',
                'store_id' => $header['store_id'] ?? null,
                'type'     => $header['type'] ?? 'Commande stock',
            ], $raw, $p);
        });
    }

    /* ═══════════════════════════ COMMANDE ═══════════════════════════ */

    /** Commande fournisseur : AUCUN effet sur le stock ni sur le coût (cela arrive à la réception). */
    public function createOrder(Supplier $supplier, array $header, array $rawLines, ?PurchaseProject $project = null): PurchaseOrder
    {
        return DB::transaction(function () use ($supplier, $header, $rawLines, $project) {
            $lines  = $this->prepareLines($supplier, $rawLines);
            $totals = $this->totals($lines);

            $type = $header['type'] ?? null;
            if (!in_array($type, ['Commande stock', 'Commande sur demande'], true)) $type = null;

            $order = PurchaseOrder::create([
                'numdoc'              => $this->nextNumber('commande_achat'),
                'supplier_id'         => $supplier->id,
                'order_date'          => $header['date'] ?? now()->toDateString(),
                'status'              => ($header['status'] ?? 'validée') === 'brouillon' ? 'brouillon' : 'validée',
                'type'                => $type,
                'notes'               => $header['notes'] ?? null,
                'total_ht'            => $totals['ht'],
                'total_ttc'           => $totals['ttc'],
                'tva_rate'            => $totals['tva_rate'],
                'store_id'            => $header['store_id'] ?? null,
                'purchase_project_id' => $project ? $project->id : null,
                'reception_status'    => 'a_recevoir',
            ]);

            foreach ($lines as $l) {
                PurchaseOrderLine::create([
                    'purchase_order_id' => $order->id,
                    'article_code'      => $l['code'],
                    'ordered_quantity'  => $l['qty'],
                    'received_quantity' => 0,
                    'unit_price_ht'     => $l['pu'],
                    'remise'            => $l['remise'],
                    'tva'               => $l['tva'],
                    'total_ligne_ht'    => $l['total_ht'],
                ]);
            }

            if ($project) {
                $project->update(['status' => 'converted']);
            }
            return $order->load('lines');
        });
    }

    /**
     * Modification d'une commande (écran « Modifier ») : lignes remplacées, totaux recalculés.
     * Refusée dès qu'une réception active existe (les quantités reçues sont liées aux lignes).
     * Ne touche NI au stock NI aux coûts. $validate = true passe un brouillon en « validée ».
     */
    public function updateOrder(PurchaseOrder $order, Supplier $supplier, array $header, array $rawLines, bool $validate = false): PurchaseOrder
    {
        return DB::transaction(function () use ($order, $supplier, $header, $rawLines, $validate) {
            $order = PurchaseOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if (Reception::where('purchase_order_id', $order->id)->where('status', '!=', 'annulée')->exists()) {
                throw new PurchaseException("La commande {$order->numdoc} a déjà des réceptions : elle ne peut plus être modifiée. Annulez d'abord les réceptions.");
            }
            if ($order->invoiced) {
                throw new PurchaseException("La commande {$order->numdoc} est déjà facturée.");
            }

            $lines  = $this->prepareLines($supplier, $rawLines);
            $totals = $this->totals($lines);

            PurchaseOrderLine::where('purchase_order_id', $order->id)->delete();
            foreach ($lines as $l) {
                PurchaseOrderLine::create([
                    'purchase_order_id' => $order->id,
                    'article_code'      => $l['code'],
                    'ordered_quantity'  => $l['qty'],
                    'received_quantity' => 0,
                    'unit_price_ht'     => $l['pu'],
                    'remise'            => $l['remise'],
                    'tva'               => $l['tva'],
                    'total_ligne_ht'    => $l['total_ht'],
                    'prix_ttc'          => $l['total_ttc'],
                ]);
            }

            $order->update([
                'supplier_id'      => $supplier->id,
                'order_date'       => $header['date'] ?? $order->order_date,
                'notes'            => $header['notes'] ?? $order->notes,
                'total_ht'         => $totals['ht'],
                'total_ttc'        => $totals['ttc'],
                'tva_rate'         => $totals['tva_rate'],
                'reception_status' => 'a_recevoir',
                'status'           => ($validate && $order->status === 'brouillon') ? 'validée' : $order->status,
            ]);

            return $order->fresh('lines');
        });
    }

    /* ═══════════════════════════ RÉCEPTION ═══════════════════════════ */

    /** Réception validée : le stock et le coût sont mis à jour ICI. */
    public function createReception(Supplier $supplier, array $header, array $rawLines, ?PurchaseOrder $order = null): Reception
    {
        return DB::transaction(function () use ($supplier, $header, $rawLines, $order) {
            return $this->doReception($supplier, $header, $rawLines, $order);
        });
    }

    private function doReception(Supplier $supplier, array $header, array $rawLines, ?PurchaseOrder $order): Reception
    {
        if ($order) {
            $order = PurchaseOrder::whereKey($order->id)->lockForUpdate()->first();
            if ((int) $order->supplier_id !== (int) $supplier->id) {
                throw new PurchaseException('Cette commande n\'appartient pas à ce fournisseur.');
            }
            if ($order->status === 'brouillon') {
                throw new PurchaseException('Validez la commande avant de la réceptionner.');
            }
        }

        $storeId = $header['store_id'] ?? ($order->store_id ?? null) ?? Store::orderBy('id')->value('id');
        if (!$storeId) throw new PurchaseException('Aucun magasin configuré : créez un magasin avant de réceptionner.');

        $lines  = $this->prepareLines($supplier, $rawLines);
        $totals = $this->totals($lines);

        // On verrouille les articles (ordre fixe : pas d'inter-blocage) avant de toucher stock et coût.
        $ids = array_values(array_unique(array_map(fn ($l) => $l['item']->id, $lines)));
        sort($ids);
        Item::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get();
        foreach ($lines as $l) { $l['item']->refresh(); }   // état à jour une fois le verrou obtenu

        $orderLines = $order ? PurchaseOrderLine::where('purchase_order_id', $order->id)->lockForUpdate()->get()->keyBy('id') : collect();

        $numdoc = $this->nextNumber('reception_achat');
        $reception = Reception::create([
            'numdoc'            => $numdoc,
            'purchase_order_id' => $order ? $order->id : null,
            'supplier_id'       => $supplier->id,
            'store_id'          => $storeId,
            'reception_date'    => $header['date'] ?? now()->toDateString(),
            'status'            => 'reçu',
            'total_received'    => array_sum(array_column($lines, 'qty')),
            'supplier_ref'      => $header['supplier_ref'] ?? null,
            'notes'             => $header['notes'] ?? null,
            'invoiced'          => false,
            'stock_applied'     => true,
        ]);

        foreach ($lines as $l) {
            $orderLineId = $l['order_line_id'];
            if ($orderLineId && !$orderLines->has($orderLineId)) {
                throw new PurchaseException("La ligne de commande {$orderLineId} n'appartient pas à cette commande.");
            }

            $rl = ReceptionLine::create([
                'reception_id'           => $reception->id,
                'purchase_order_line_id' => $orderLineId,
                'article_code'           => $l['code'],
                'received_quantity'      => $l['qty'],
                'unit_price_ht'          => $l['pu'],
                'remise'                 => $l['remise'],
                'tva'                    => $l['tva'],
                'total_ligne_ht'         => $l['total_ht'],
            ]);

            $this->addStock($l['item'], (int) $storeId, $l['qty'], $l['net_unit'], $supplier, $numdoc, $rl->id);
            $this->applyPurchaseCost($l['item'], $supplier, $l['pu'], $l['remise']);

            if ($orderLineId) {
                $ol = $orderLines->get($orderLineId);
                $ol->received_quantity = (float) $ol->received_quantity + $l['qty'];
                $ol->save();
            }
        }

        if ($order) $this->refreshOrderReception($order);

        return $reception->load('lines');
    }

    private function addStock(Item $item, int $storeId, float $qty, float $netCost, Supplier $supplier, string $ref, int $receptionLineId): void
    {
        $this->moveStock($item, $storeId, $qty, 'achat', $netCost, $supplier->name, $ref, 'Réception ' . $ref, $receptionLineId);
    }

    /** Mouvement de stock (entrée si $delta > 0, sortie si < 0). Refuse de passer sous 0. */
    private function moveStock(Item $item, int $storeId, float $delta, string $type, float $netCost, string $supplierName, string $ref, string $note, ?int $receptionLineId): void
    {
        $stock = Stock::where('item_id', $item->id)->where('store_id', $storeId)->lockForUpdate()->first()
            ?: new Stock(['item_id' => $item->id, 'store_id' => $storeId, 'quantity' => 0]);
        $new = round((float) $stock->quantity + $delta, 2);
        if ($new < -0.00001) {
            throw new PurchaseException("Stock insuffisant pour {$item->code} : il reste " . (float) $stock->quantity . " en magasin, impossible de ressortir " . abs($delta) . " (déjà vendu ou sorti).");
        }
        $stock->quantity = $new;
        $stock->save();

        StockMovement::create([
            'item_id'           => $item->id,
            'store_id'          => $storeId,
            'type'              => $type,
            'quantity'          => $delta,
            'cost_price'        => round($netCost, 4),
            'supplier_name'     => $supplierName,
            'reference'         => $ref,
            'note'              => $note,
            'reception_line_id' => $receptionLineId,
        ]);
    }

    /** Recalcule l'état de réception d'une commande à partir de ses lignes. */
    public function refreshOrderReception(PurchaseOrder $order): void
    {
        $lines = PurchaseOrderLine::where('purchase_order_id', $order->id)->get();
        $ordered = 0; $received = 0; $complete = true;
        foreach ($lines as $l) {
            $ordered  += (float) $l->ordered_quantity;
            $received += (float) $l->received_quantity;
            if ((float) $l->received_quantity + 0.00001 < (float) $l->ordered_quantity) $complete = false;
        }
        $state = $received <= 0 ? 'a_recevoir' : ($complete ? 'recue' : 'partielle');
        $order->update(['reception_status' => $state]);
    }

    /* ═══════════════════════════ FACTURE ═══════════════════════════ */

    /**
     * Facture validée à partir de réceptions existantes (jamais de ressaisie : on facture ce qu'on a reçu).
     *
     * @param  Reception[]  $receptions
     */
    public function createInvoiceForReceptions(Supplier $supplier, array $header, array $receptions, string $type = 'groupée', array $returns = []): PurchaseInvoice
    {
        return DB::transaction(function () use ($supplier, $header, $receptions, $type, $returns) {
            return $this->doInvoice($supplier, $header, $receptions, $type, $returns);
        });
    }

    /**
     * Retours fournisseur validés et pas encore facturés / couverts par un avoir, pour un fournisseur
     * (candidats à la facture de fin de mois : ils viennent en déduction des réceptions).
     */
    public function unbilledReturns(Supplier $supplier)
    {
        return PurchaseReturn::with(['lines', 'purchaseOrder'])
            ->where('supplier_id', $supplier->id)->where('invoiced', 0)
            ->where(function ($q) { $q->whereNull('status')->orWhere('status', '!=', 'annulée'); })
            ->orderBy('return_date')->orderBy('id')->get();
    }

    /**
     * @param  PurchaseReturn[]|int[]  $returns  retours à déduire sur la même facture (lignes négatives)
     */
    private function doInvoice(Supplier $supplier, array $header, array $receptions, string $type, array $returns = []): PurchaseInvoice
    {
        $ids = array_map(fn ($r) => $r->id, $receptions);
        $recs = Reception::with('lines')->whereIn('id', $ids)->lockForUpdate()->get();
        if ($recs->isEmpty()) throw new PurchaseException('Aucune réception à facturer.');
        foreach ($recs as $r) {
            if ((int) $r->supplier_id !== (int) $supplier->id) throw new PurchaseException("La réception {$r->numdoc} n'appartient pas à ce fournisseur.");
            if ($r->invoiced) throw new PurchaseException("La réception {$r->numdoc} est déjà facturée.");
            if ($r->status === 'annulée') throw new PurchaseException("La réception {$r->numdoc} est annulée : elle ne peut pas être facturée.");
            if (strpos((string) $r->numdoc, 'REC-H') === 0) throw new PurchaseException("La réception {$r->numdoc} date de l'ancien circuit (déjà gérée avant la migration) : elle ne se facture pas ici.");
        }

        $lines = [];
        foreach ($recs as $r) {
            foreach ($r->lines as $rl) {
                $ht = round((float) $rl->total_ligne_ht, 2);
                $lines[] = [
                    'rec' => $r, 'code' => $rl->article_code, 'qty' => (float) $rl->received_quantity,
                    'pu' => (float) $rl->unit_price_ht, 'remise' => (float) $rl->remise, 'tva' => (float) $rl->tva,
                    'total_ht' => $ht, 'total_ttc' => round($ht * (1 + (float) $rl->tva / 100), 2),
                ];
            }
        }

        // Retours fournisseur à déduire : lignes négatives (même convention que les avoirs)
        $retIds = array_values(array_unique(array_map(function ($r) { return is_object($r) ? (int) $r->id : (int) $r; }, $returns)));
        $retDocs = collect();
        if ($retIds) {
            $retDocs = PurchaseReturn::with('lines')->whereIn('id', $retIds)->lockForUpdate()->get();
            if ($retDocs->count() !== count($retIds)) throw new PurchaseException('Un des retours sélectionnés est introuvable.');
            foreach ($retDocs as $rt) {
                if ((int) $rt->supplier_id !== (int) $supplier->id) throw new PurchaseException("Le retour {$rt->numdoc} n'appartient pas à ce fournisseur.");
                if ($rt->status === 'annulée') throw new PurchaseException("Le retour {$rt->numdoc} est annulé.");
                if ($rt->invoiced) throw new PurchaseException("Le retour {$rt->numdoc} est déjà facturé ou couvert par un avoir.");
                foreach ($rt->lines as $rl) {
                    $ht = -round(abs((float) $rl->total_ligne_ht), 2);
                    $tv = $rl->tva !== null ? (float) $rl->tva : (float) ($rt->tva_rate ?? 0);
                    $lines[] = [
                        'rec' => null, 'ret' => $rt, 'code' => $rl->article_code, 'qty' => -abs((float) $rl->returned_quantity),
                        'pu' => (float) $rl->unit_price_ht, 'remise' => (float) $rl->remise, 'tva' => $tv,
                        'total_ht' => $ht, 'total_ttc' => round($ht * (1 + $tv / 100), 2),
                    ];
                }
            }
        }
        $totals = $this->totals($lines);
        if ($totals['ttc'] < 0) {
            throw new PurchaseException('Les retours sélectionnés dépassent les réceptions facturées (total négatif) : désélectionnez des retours ou faites un avoir.');
        }

        $invoice = PurchaseInvoice::create([
            'numdoc'       => $this->nextNumber('facture_achat'),
            'supplier_id'  => $supplier->id,
            'invoice_date' => $header['invoice_date'] ?? ($header['date'] ?? now()->toDateString()),
            'due_date'     => $header['due_date'] ?? null,
            'status'       => 'validée',
            'type'         => $type,
            'total_ht'     => $totals['ht'],
            'total_ttc'    => $totals['ttc'],
            'tva_rate'     => $totals['tva_rate'],
            'notes'        => $header['notes'] ?? null,
            'supplier_ref' => $header['supplier_ref'] ?? null,
        ]);

        foreach ($lines as $l) {
            PurchaseInvoiceLine::create([
                'purchase_invoice_id' => $invoice->id,
                'article_code'        => $l['code'],
                'purchase_order_id'   => $l['rec'] ? $l['rec']->purchase_order_id : ($l['ret']->purchase_order_id ?? null),
                'reception_id'        => $l['rec'] ? $l['rec']->id : null,
                'purchase_return_id'  => isset($l['ret']) ? $l['ret']->id : null,
                'quantity'            => $l['qty'],
                'unit_price_ht'       => $l['pu'],
                'remise'              => $l['remise'],
                'total_ligne_ht'      => $l['total_ht'],
                'tva'                 => $l['tva'],
                'prix_ttc'            => $l['total_ttc'],
            ]);
        }

        Reception::whereIn('id', $ids)->update(['invoiced' => 1]);
        if ($retIds) PurchaseReturn::whereIn('id', $retIds)->update(['invoiced' => 1]);

        // Une commande est « facturée » quand elle est entièrement reçue et que toutes ses réceptions sont facturées
        $orderIds = $recs->pluck('purchase_order_id')->filter()->unique();
        foreach ($orderIds as $oid) {
            $order = PurchaseOrder::find($oid);
            $open  = Reception::where('purchase_order_id', $oid)->where('invoiced', 0)->exists();
            if ($order && $order->reception_status === 'recue' && !$open) {
                $order->update(['invoiced' => 1]);
            }
        }

        // Solde fournisseur : positif = nous lui devons (même convention qu'avant)
        $locked = Supplier::whereKey($supplier->id)->lockForUpdate()->first();
        $locked->solde = round((float) ($locked->solde ?? 0) + $totals['ttc'], 2);
        $locked->save();

        return $invoice->load('lines');
    }

    /**
     * « Facturation directe » : on réceptionne ET on facture en une seule fois.
     * Avec $order, c'est la réception d'une commande existante, immédiatement facturée.
     */
    public function receiveAndInvoice(Supplier $supplier, array $header, array $rawLines, ?PurchaseOrder $order = null): array
    {
        return DB::transaction(function () use ($supplier, $header, $rawLines, $order) {
            $reception = $this->doReception($supplier, $header, $rawLines, $order);
            $invoice   = $this->doInvoice($supplier, $header, [$reception], $order ? 'groupée' : 'direct');
            return ['reception' => $reception->fresh('lines'), 'invoice' => $invoice];
        });
    }

    /* ═══════════════════════ RECTIFICATION / ANNULATION ═══════════════════════ */

    /**
     * Rectifie une réception NON facturée : quantités, prix, remise, TVA des lignes (et N° BL / notes / date).
     *  - le stock est ajusté de la différence (mouvement « correction_achat ») ; jamais de stock négatif ;
     *  - la quantité reçue de la ligne de commande suit, l'état de la commande est recalculé ;
     *  - une quantité 0 retire la ligne ; si TOUTES les lignes tombent à 0, il faut annuler la réception ;
     *  - le coût de l'article est mis à jour seulement si cette réception est la plus récente pour l'article.
     *
     * @param array<int,array{qty?:mixed,pu?:mixed,remise?:mixed,tva?:mixed}> $changes  indexé par id de ligne de réception
     */
    public function correctReception(int $receptionId, array $changes, array $header = []): Reception
    {
        return DB::transaction(function () use ($receptionId, $changes, $header) {
            $rec = Reception::whereKey($receptionId)->lockForUpdate()->first();
            if (!$rec) throw new PurchaseException('Réception introuvable.');
            $this->assertEditable($rec);

            $supplier = Supplier::findOrFail($rec->supplier_id);
            $lines    = ReceptionLine::where('reception_id', $rec->id)->orderBy('id')->lockForUpdate()->get();
            $order    = $rec->purchase_order_id ? PurchaseOrder::whereKey($rec->purchase_order_id)->lockForUpdate()->first() : null;
            $olines   = $order ? PurchaseOrderLine::where('purchase_order_id', $order->id)->lockForUpdate()->get()->keyBy('id') : collect();
            $storeId  = (int) ($rec->store_id ?: Store::orderBy('id')->value('id'));

            $remaining = 0;
            foreach ($lines as $rl) {
                $ch  = $changes[$rl->id] ?? [];
                $n   = 'Ligne ' . $rl->article_code;
                $old = (int) round((float) $rl->received_quantity);

                $qty = array_key_exists('qty', $ch) ? $this->num($ch['qty']) : $old;
                if ($qty < 0 || abs($qty - round($qty)) > 0.00001) throw new PurchaseException("$n : la quantité doit être un entier positif ou nul.");
                $qty = (int) round($qty);
                $pu  = array_key_exists('pu', $ch) ? $this->num($ch['pu']) : (float) $rl->unit_price_ht;
                $rem = array_key_exists('remise', $ch) ? $this->num($ch['remise']) : (float) $rl->remise;
                $tva = array_key_exists('tva', $ch) ? $this->num($ch['tva']) : (float) $rl->tva;
                if ($pu < 0)                  throw new PurchaseException("$n : le prix ne peut pas être négatif.");
                if ($rem < 0 || $rem > 100)   throw new PurchaseException("$n : la remise doit être comprise entre 0 et 100 %.");
                if ($tva < 0 || $tva > 100)   throw new PurchaseException("$n : la TVA doit être comprise entre 0 et 100 %.");

                $item  = Item::where('code', $rl->article_code)->lockForUpdate()->first();
                if (!$item) throw new PurchaseException("$n : article introuvable.");
                $delta = $qty - $old;
                $net   = $pu * (1 - $rem / 100);

                if ($delta !== 0) {
                    $this->moveStock($item, $storeId, $delta, 'correction_achat', $net, $supplier->name, $rec->numdoc,
                        "Correction réception {$rec->numdoc} ({$old} → {$qty})", $rl->id);
                    if ($rl->purchase_order_line_id && $olines->has($rl->purchase_order_line_id)) {
                        $ol = $olines->get($rl->purchase_order_line_id);
                        $ol->received_quantity = max(0, (float) $ol->received_quantity + $delta);
                        $ol->save();
                    }
                }

                $priceChanged = abs($pu - (float) $rl->unit_price_ht) > 0.00001 || abs($rem - (float) $rl->remise) > 0.00001;
                if ($priceChanged) {
                    StockMovement::where('reception_line_id', $rl->id)->where('type', 'achat')->update(['cost_price' => round($net, 4)]);
                    $later = ReceptionLine::where('article_code', $rl->article_code)->where('id', '>', $rl->id)
                        ->whereHas('reception', function ($q) { $q->where('status', '!=', 'annulée'); })->exists();
                    if (!$later && $qty > 0) $this->applyPurchaseCost($item->refresh(), $supplier, $pu, $rem);
                }

                if ($qty === 0) {
                    $rl->delete();
                    continue;
                }
                $remaining++;
                $rl->received_quantity = $qty;
                $rl->unit_price_ht     = round($pu, 2);
                $rl->remise            = round($rem, 2);
                $rl->tva               = round($tva, 2);
                $rl->total_ligne_ht    = round($qty * $net, 2);
                $rl->save();
            }

            if ($remaining === 0) {
                throw new PurchaseException('Toutes les quantités sont à 0 : utilisez « Annuler la réception ».');
            }

            $rec->update([
                'total_received' => (int) ReceptionLine::where('reception_id', $rec->id)->sum('received_quantity'),
                'supplier_ref'   => array_key_exists('supplier_ref', $header) ? $header['supplier_ref'] : $rec->supplier_ref,
                'notes'          => array_key_exists('notes', $header) ? $header['notes'] : $rec->notes,
                'reception_date' => $header['date'] ?? $rec->reception_date,
            ]);
            if ($order) $this->refreshOrderReception($order);

            return $rec->fresh('lines');
        });
    }

    /** Annule une réception NON facturée : le stock est ressorti, la commande redevient « à recevoir / partielle ». */
    public function cancelReception(int $receptionId, ?string $reason = null): Reception
    {
        return DB::transaction(function () use ($receptionId, $reason) {
            $rec = Reception::whereKey($receptionId)->lockForUpdate()->first();
            if (!$rec) throw new PurchaseException('Réception introuvable.');
            $this->assertEditable($rec);

            $supplier = Supplier::findOrFail($rec->supplier_id);
            $lines    = ReceptionLine::where('reception_id', $rec->id)->orderBy('id')->lockForUpdate()->get();
            $order    = $rec->purchase_order_id ? PurchaseOrder::whereKey($rec->purchase_order_id)->lockForUpdate()->first() : null;
            $storeId  = (int) ($rec->store_id ?: Store::orderBy('id')->value('id'));

            foreach ($lines as $rl) {
                $qty  = (int) round((float) $rl->received_quantity);
                $item = Item::where('code', $rl->article_code)->lockForUpdate()->first();
                if (!$item || $qty <= 0) continue;
                $net = (float) $rl->unit_price_ht * (1 - (float) $rl->remise / 100);
                $this->moveStock($item, $storeId, -$qty, 'annulation_achat', $net, $supplier->name, $rec->numdoc,
                    "Annulation réception {$rec->numdoc}", $rl->id);
                if ($order && $rl->purchase_order_line_id) {
                    $ol = PurchaseOrderLine::whereKey($rl->purchase_order_line_id)->lockForUpdate()->first();
                    if ($ol) { $ol->received_quantity = max(0, (float) $ol->received_quantity - $qty); $ol->save(); }
                }
            }

            $rec->update([
                'status'        => 'annulée',
                'stock_applied' => false,
                'notes'         => trim(($rec->notes ? $rec->notes . "\n" : '') . 'Annulée' . ($reason ? ' : ' . $reason : '') . ' (' . now()->format('d/m/Y H:i') . ')'),
            ]);
            if ($order) $this->refreshOrderReception($order);

            return $rec->fresh('lines');
        });
    }

    /* ═══════════════════════════ FACTURES ═══════════════════════════ */

    /**
     * Lignes d'une facture LIBRE (hors stock) : soit une désignation libre, soit un code article
     * (le code sert seulement à la mémoire de la ligne : aucun stock, aucun coût n'est modifié).
     */
    private function freeLines(Supplier $supplier, array $raw, ?float $defaultTva): array
    {
        $fallback = $defaultTva !== null ? $defaultTva : (float) ($supplier->tvaGroup->rate ?? 0);
        $out = [];
        foreach ($raw as $i => $r) {
            $code = trim((string) ($r['article_code'] ?? ''));
            $desc = trim((string) ($r['description'] ?? ''));
            $qty  = $this->num($r['quantity'] ?? 0);
            if ($code === '' && $desc === '' && $qty == 0.0) continue; // ligne vide
            if ($code === '' && $desc === '') throw new PurchaseException('Ligne ' . ((int) $i + 1) . ' : saisissez une désignation.');
            if ($qty == 0.0) throw new PurchaseException('Ligne ' . ((int) $i + 1) . ' : la quantité ne peut pas être nulle.');
            $pu     = $this->num($r['unit_price_ht'] ?? 0);
            $remise = $this->num($r['remise'] ?? 0);
            if ($remise < 0 || $remise > 100) throw new PurchaseException('Ligne ' . ((int) $i + 1) . ' : la remise doit être comprise entre 0 et 100 %.');
            $tva = isset($r['tva']) && $r['tva'] !== '' ? $this->num($r['tva']) : $fallback;
            if ($tva < 0) throw new PurchaseException('Ligne ' . ((int) $i + 1) . ' : TVA invalide.');
            $ht = round($qty * $pu * (1 - $remise / 100), 2);
            $out[] = [
                'code' => $code !== '' ? $code : null, 'desc' => $desc !== '' ? $desc : null,
                'qty' => $qty, 'pu' => $pu, 'remise' => $remise, 'tva' => $tva,
                'total_ht' => $ht, 'total_ttc' => round($ht * (1 + $tva / 100), 2),
                'order_id' => $r['purchase_order_id'] ?? null, 'return_id' => $r['purchase_return_id'] ?? null,
            ];
        }
        if (!$out) throw new PurchaseException('Ajoutez au moins une ligne à la facture.');
        return $out;
    }

    private function writeInvoiceLines(PurchaseInvoice $inv, array $lines): void
    {
        foreach ($lines as $l) {
            PurchaseInvoiceLine::create([
                'purchase_invoice_id' => $inv->id,
                'article_code'        => $l['code'],
                'purchase_order_id'   => $l['order_id'] ?: null,
                'purchase_return_id'  => $l['return_id'] ?: null,
                'quantity'            => $l['qty'],
                'unit_price_ht'       => $l['pu'],
                'remise'              => $l['remise'],
                'total_ligne_ht'      => $l['total_ht'],
                'tva'                 => $l['tva'],
                'prix_ttc'            => $l['total_ttc'],
                'description'         => $l['desc'],
            ]);
        }
    }

    private function addSolde(int $supplierId, float $delta): void
    {
        $sup = Supplier::whereKey($supplierId)->lockForUpdate()->first();
        if ($sup) {
            $sup->solde = round((float) ($sup->solde ?? 0) + $delta, 2);
            $sup->save();
        }
    }

    /** Facture libre (hors stock). $validate = false → brouillon (aucun effet sur le solde). */
    public function createFreeInvoice(Supplier $supplier, array $header, array $rawLines, bool $validate = false): PurchaseInvoice
    {
        return DB::transaction(function () use ($supplier, $header, $rawLines, $validate) {
            $lines  = $this->freeLines($supplier, $rawLines, isset($header['tva_rate']) && $header['tva_rate'] !== '' ? (float) $header['tva_rate'] : null);
            $totals = $this->totals($lines);

            $inv = PurchaseInvoice::create([
                'numdoc'       => $this->nextNumber('facture_achat'),
                'supplier_id'  => $supplier->id,
                'invoice_date' => $header['invoice_date'] ?? now()->toDateString(),
                'due_date'     => $header['due_date'] ?? null,
                'status'       => $validate ? 'validée' : 'brouillon',
                'type'         => 'libre',
                'total_ht'     => $totals['ht'],
                'total_ttc'    => $totals['ttc'],
                'tva_rate'     => $totals['tva_rate'],
                'notes'        => $header['notes'] ?? null,
                'supplier_ref' => $header['supplier_ref'] ?? null,
            ]);
            $this->writeInvoiceLines($inv, $lines);
            if ($validate) $this->addSolde($supplier->id, $totals['ttc']);

            return $inv->load('lines');
        });
    }

    /**
     * Modification d'un BROUILLON (libre, ou ancien brouillon direct/groupée : les liens vers les
     * commandes / retours sont conservés ligne à ligne quand l'article correspond).
     */
    public function updateDraftInvoice(int $invoiceId, Supplier $supplier, array $header, array $rawLines, bool $validate = false): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoiceId, $supplier, $header, $rawLines, $validate) {
            $inv = PurchaseInvoice::whereKey($invoiceId)->lockForUpdate()->firstOrFail();
            if ($inv->status !== 'brouillon') {
                throw new PurchaseException("La facture {$inv->numdoc} n'est plus un brouillon : seules ses mentions (référence, échéance, notes) sont modifiables.");
            }

            $old = PurchaseInvoiceLine::where('purchase_invoice_id', $inv->id)->get();
            foreach ($rawLines as $k => $r) {
                if (!empty($r['purchase_order_id']) || !empty($r['purchase_return_id']) || empty($r['article_code'])) continue;
                $m = $old->first(function ($ol) use ($r) { return $ol->article_code === $r['article_code'] && ($ol->purchase_order_id || $ol->purchase_return_id); });
                if ($m) {
                    $rawLines[$k]['purchase_order_id']  = $m->purchase_order_id;
                    $rawLines[$k]['purchase_return_id'] = $m->purchase_return_id;
                    $old = $old->reject(function ($ol) use ($m) { return $ol->id === $m->id; });
                }
            }

            $lines  = $this->freeLines($supplier, $rawLines, isset($header['tva_rate']) && $header['tva_rate'] !== '' ? (float) $header['tva_rate'] : null);
            $totals = $this->totals($lines);

            PurchaseInvoiceLine::where('purchase_invoice_id', $inv->id)->delete();
            $this->writeInvoiceLines($inv, $lines);

            $inv->update([
                'supplier_id'  => $supplier->id,
                'invoice_date' => $header['invoice_date'] ?? $inv->invoice_date,
                'due_date'     => $header['due_date'] ?? null,
                'notes'        => $header['notes'] ?? null,
                'supplier_ref' => $header['supplier_ref'] ?? null,
                'total_ht'     => $totals['ht'],
                'total_ttc'    => $totals['ttc'],
                'tva_rate'     => $totals['tva_rate'],
                'status'       => $validate ? 'validée' : 'brouillon',
            ]);
            if ($validate) $this->addSolde($supplier->id, $totals['ttc']);

            return $inv->fresh('lines');
        });
    }

    /** Commande validée AVANT la migration : stock déjà entré, jamais facturée, aucune réception du nouveau circuit. */
    public function isLegacyUninvoicedOrder(PurchaseOrder $order): bool
    {
        if ($order->status !== 'validée' || $order->invoiced || $order->reception_status !== 'recue') return false;
        $recs = Reception::where('purchase_order_id', $order->id)->where('status', '!=', 'annulée')->get();
        if ($recs->isEmpty()) return false;
        foreach ($recs as $r) {
            if (strpos((string) $r->numdoc, 'REC-H') !== 0) return false;
        }
        return true;
    }

    /**
     * Facture d'anciennes commandes (déjà « reçues » à la migration) : le stock est DÉJÀ entré, la facture
     * reprend simplement les lignes de la/des commande(s). Une commande → type « direct », plusieurs → « groupée ».
     */
    public function invoiceLegacyOrders(Supplier $supplier, array $header, array $orders): PurchaseInvoice
    {
        return DB::transaction(function () use ($supplier, $header, $orders) {
            $ids = array_map(function ($o) { return is_object($o) ? $o->id : (int) $o; }, $orders);
            $rows = PurchaseOrder::with('lines')->whereIn('id', $ids)->lockForUpdate()->get();
            if ($rows->isEmpty()) throw new PurchaseException('Aucune commande à facturer.');

            $lines = [];
            foreach ($rows as $o) {
                if ((int) $o->supplier_id !== (int) $supplier->id) throw new PurchaseException("La commande {$o->numdoc} n'appartient pas à ce fournisseur.");
                if (!$this->isLegacyUninvoicedOrder($o)) {
                    throw new PurchaseException("La commande {$o->numdoc} ne se facture pas ainsi : utilisez ses réceptions (ou elle est déjà facturée).");
                }
                foreach ($o->lines as $l) {
                    $ht  = round((float) $l->ordered_quantity * (float) $l->unit_price_ht * (1 - (float) $l->remise / 100), 2);
                    $tva = $l->tva !== null ? (float) $l->tva : (float) ($o->tva_rate ?? 0);
                    $lines[] = [
                        'code' => $l->article_code, 'desc' => null, 'qty' => (float) $l->ordered_quantity, 'pu' => (float) $l->unit_price_ht,
                        'remise' => (float) $l->remise, 'tva' => $tva, 'total_ht' => $ht, 'total_ttc' => round($ht * (1 + $tva / 100), 2),
                        'order_id' => $o->id, 'return_id' => null,
                    ];
                }
            }
            $totals = $this->totals($lines);

            $inv = PurchaseInvoice::create([
                'numdoc'       => $this->nextNumber('facture_achat'),
                'supplier_id'  => $supplier->id,
                'invoice_date' => $header['invoice_date'] ?? now()->toDateString(),
                'due_date'     => $header['due_date'] ?? null,
                'status'       => 'validée',
                'type'         => $rows->count() > 1 ? 'groupée' : 'direct',
                'total_ht'     => $totals['ht'],
                'total_ttc'    => $totals['ttc'],
                'tva_rate'     => $totals['tva_rate'],
                'notes'        => $header['notes'] ?? null,
                'supplier_ref' => $header['supplier_ref'] ?? null,
            ]);
            $this->writeInvoiceLines($inv, $lines);
            PurchaseOrder::whereIn('id', $rows->pluck('id'))->update(['invoiced' => 1]);
            $this->addSolde($supplier->id, $totals['ttc']);

            return $inv->load('lines');
        });
    }

    /** Valide un brouillon tel quel (le solde fournisseur augmente du TTC). */
    public function validateInvoice(int $invoiceId): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoiceId) {
            $inv = PurchaseInvoice::whereKey($invoiceId)->lockForUpdate()->firstOrFail();
            if ($inv->status !== 'brouillon') throw new PurchaseException("La facture {$inv->numdoc} n'est pas un brouillon.");
            if (!PurchaseInvoiceLine::where('purchase_invoice_id', $inv->id)->exists()) throw new PurchaseException("La facture {$inv->numdoc} n'a aucune ligne.");
            $inv->update(['status' => 'validée']);
            $this->addSolde($inv->supplier_id, (float) $inv->total_ttc);
            return $inv;
        });
    }

    /** Mentions d'une facture déjà validée : référence fournisseur, échéance, date, notes (jamais les montants). */
    public function updateInvoiceHeader(int $invoiceId, array $header): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoiceId, $header) {
            $inv = PurchaseInvoice::whereKey($invoiceId)->lockForUpdate()->firstOrFail();
            if ($inv->status === 'annulée') throw new PurchaseException("La facture {$inv->numdoc} est annulée.");
            $data = [];
            foreach (['supplier_ref', 'due_date', 'notes', 'invoice_date'] as $k) {
                if (array_key_exists($k, $header)) $data[$k] = ($header[$k] === '' ? null : $header[$k]);
            }
            if (array_key_exists('invoice_date', $data) && $data['invoice_date'] === null) unset($data['invoice_date']);
            $inv->update($data);
            return $inv;
        });
    }

    /**
     * Annule une facture (brouillon ou validée) : le solde fournisseur est corrigé, les réceptions /
     * commandes / retours qu'elle couvrait redeviennent « à facturer ». Le numéro reste dans l'historique.
     * Refusée s'il existe des règlements ou un avoir actif.
     */
    public function cancelInvoice(int $invoiceId, ?string $reason = null): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoiceId, $reason) {
            $inv = PurchaseInvoice::whereKey($invoiceId)->lockForUpdate()->firstOrFail();
            if ($inv->status === 'annulée') throw new PurchaseException("La facture {$inv->numdoc} est déjà annulée.");
            if ($inv->payments()->exists()) throw new PurchaseException("La facture {$inv->numdoc} a des règlements : annulez d'abord les règlements.");
            if ($this->hasActiveNote('purchase_invoice_id', $inv->id)) {
                throw new PurchaseException("La facture {$inv->numdoc} a un avoir : annulez d'abord l'avoir.");
            }

            $lines = PurchaseInvoiceLine::where('purchase_invoice_id', $inv->id)->get();
            $recIds = $lines->pluck('reception_id')->filter()->unique()->values()->all();
            if ($recIds) Reception::whereIn('id', $recIds)->update(['invoiced' => 0]);

            $orderIds = $lines->pluck('purchase_order_id')->filter()->unique()->values()->all();
            if ($orderIds) PurchaseOrder::whereIn('id', $orderIds)->update(['invoiced' => 0]);

            $retIds = $lines->pluck('purchase_return_id')->filter()->unique()->values()->all();
            if ($retIds && class_exists(\App\Models\PurchaseReturn::class)) \App\Models\PurchaseReturn::whereIn('id', $retIds)->update(['invoiced' => 0]);

            if ($inv->status === 'validée') $this->addSolde($inv->supplier_id, -(float) $inv->total_ttc);

            $note = '[Annulée le ' . now()->format('d/m/Y') . ($reason ? ' : ' . $reason : '') . ']';
            $inv->update(['status' => 'annulée', 'paid' => 0, 'notes' => trim(($inv->notes ? $inv->notes . "\n" : '') . $note)]);
            return $inv;
        });
    }

    /* ═══════════════════════════ RETOURS ═══════════════════════════ */

    /**
     * Ce qu'on peut encore retourner pour une commande : quantité REÇUE − déjà retournée (retours non annulés).
     * @return array<string,array{code:string,name:?string,received:float,returned:float,max:float,pu:float,remise:float,tva:float}>
     */
    public function returnableForOrder(PurchaseOrder $order): array
    {
        $out = [];
        foreach (PurchaseOrderLine::where('purchase_order_id', $order->id)->get() as $l) {
            $c = $l->article_code;
            if (!isset($out[$c])) {
                $out[$c] = ['code' => $c, 'name' => optional(Item::where('code', $c)->first())->name, 'received' => 0.0, 'returned' => 0.0, 'max' => 0.0,
                            'pu' => (float) $l->unit_price_ht, 'remise' => (float) $l->remise, 'tva' => $l->tva !== null ? (float) $l->tva : (float) ($order->tva_rate ?? 0)];
            }
            $out[$c]['received'] += (float) $l->received_quantity;
        }
        $rows = PurchaseReturnLine::query()
            ->join('purchase_returns', 'purchase_returns.id', '=', 'purchase_return_lines.purchase_return_id')
            ->where('purchase_returns.purchase_order_id', $order->id)
            ->where(function ($q) { $q->whereNull('purchase_returns.status')->orWhere('purchase_returns.status', '!=', 'annulée'); })
            ->selectRaw('purchase_return_lines.article_code as code, SUM(purchase_return_lines.returned_quantity) as qty')
            ->groupBy('purchase_return_lines.article_code')->get();
        foreach ($rows as $r) {
            if (isset($out[$r->code])) $out[$r->code]['returned'] = (float) $r->qty;
        }
        foreach ($out as $c => $v) {
            $out[$c]['max'] = max(0.0, round($v['received'] - $v['returned'], 2));
        }
        return $out;
    }

    /**
     * Retour fournisseur sur une commande : le stock ressort (jamais en négatif), la quantité retournée
     * est plafonnée à « reçu − déjà retourné ». $qtyByCode = [code => quantité]. $total = true : tout le retournable.
     */
    public function createReturn(PurchaseOrder $order, array $header, array $qtyByCode, bool $total = false): PurchaseReturn
    {
        return DB::transaction(function () use ($order, $header, $qtyByCode, $total) {
            $order = PurchaseOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->status !== 'validée') throw new PurchaseException("La commande {$order->numdoc} doit être validée pour créer un retour.");
            $supplier = Supplier::findOrFail($order->supplier_id);
            $avail = $this->returnableForOrder($order);

            $wanted = [];
            if ($total) {
                foreach ($avail as $c => $v) { if ($v['max'] > 0) $wanted[$c] = $v['max']; }
            } else {
                foreach ($qtyByCode as $c => $q) {
                    $q = $this->num($q);
                    if ($q <= 0) continue;
                    if (!isset($avail[$c])) throw new PurchaseException("Article {$c} : il ne figure pas sur la commande {$order->numdoc}.");
                    if ($q > $avail[$c]['max'] + 0.00001) {
                        throw new PurchaseException("Article {$c} : quantité retournée invalide (reçu {$avail[$c]['received']}, déjà retourné {$avail[$c]['returned']}, maximum {$avail[$c]['max']}).");
                    }
                    $wanted[$c] = $q;
                }
            }
            if (!$wanted) throw new PurchaseException($total ? 'Rien à retourner : tout a déjà été retourné ou rien n\'a été reçu.' : 'Sélectionnez au moins un article avec une quantité.');

            $numdoc  = $this->nextNumber('retour_achat');
            $storeId = (int) ($order->store_id ?? 1);   // même magasin à l'annulation du retour
            $lines = [];
            foreach ($wanted as $c => $q) {
                $a   = $avail[$c];
                $ht  = round($q * $a['pu'] * (1 - $a['remise'] / 100), 2);
                $lines[] = ['code' => $c, 'qty' => $q, 'pu' => $a['pu'], 'remise' => $a['remise'], 'tva' => $a['tva'],
                            'total_ht' => $ht, 'total_ttc' => round($ht * (1 + $a['tva'] / 100), 2)];
            }
            $totals = $this->totals($lines);

            $ret = PurchaseReturn::create([
                'purchase_order_id' => $order->id, 'supplier_id' => $supplier->id, 'numdoc' => $numdoc,
                'return_date' => $header['date'] ?? now()->toDateString(), 'type' => $total ? 'total' : 'partiel',
                'total_ht' => $totals['ht'], 'total_ttc' => $totals['ttc'], 'tva_rate' => $totals['tva_rate'],
                'invoiced' => 0, 'notes' => $header['notes'] ?? null, 'status' => 'validée',
            ]);

            foreach ($lines as $l) {
                $item = Item::where('code', $l['code'])->first();
                if (!$item) throw new PurchaseException("Article {$l['code']} introuvable.");
                $net = $l['pu'] * (1 - $l['remise'] / 100);
                $this->moveStock($item, $storeId, -$l['qty'], 'retour_achat', $net, $supplier->name, $numdoc, 'Retour achat ' . $numdoc, null);
                PurchaseReturnLine::create([
                    'purchase_return_id' => $ret->id, 'article_code' => $l['code'], 'returned_quantity' => $l['qty'],
                    'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'total_ligne_ht' => $l['total_ht'], 'tva' => $l['tva'],
                ]);
            }

            return $ret->load('lines');
        });
    }

    /** Annule un retour non encore facturé / sans avoir : le stock revient, le numéro reste dans l'historique. */
    public function cancelReturn(int $returnId, ?string $reason = null): PurchaseReturn
    {
        return DB::transaction(function () use ($returnId, $reason) {
            $ret = PurchaseReturn::whereKey($returnId)->lockForUpdate()->firstOrFail();
            if ($ret->status === 'annulée') throw new PurchaseException("Le retour {$ret->numdoc} est déjà annulé.");
            if ($ret->invoiced) throw new PurchaseException("Le retour {$ret->numdoc} est déjà facturé ou couvert par un avoir : annulez d'abord la facture ou l'avoir.");
            if ($this->hasActiveNote('purchase_return_id', $ret->id)) {
                throw new PurchaseException("Le retour {$ret->numdoc} a un avoir : annulez d'abord l'avoir.");
            }
            $order   = $ret->purchase_order_id ? PurchaseOrder::find($ret->purchase_order_id) : null;
            $storeId = (int) ($order->store_id ?? 1);
            $supplier = Supplier::find($ret->supplier_id);
            foreach (PurchaseReturnLine::where('purchase_return_id', $ret->id)->get() as $l) {
                $item = Item::where('code', $l->article_code)->first();
                if (!$item) continue;
                $net = (float) $l->unit_price_ht * (1 - (float) $l->remise / 100);
                $this->moveStock($item, $storeId, (float) $l->returned_quantity, 'annulation_retour_achat', $net, $supplier->name ?? '', $ret->numdoc, 'Annulation du retour ' . $ret->numdoc, null);
            }
            $ret->update(['status' => 'annulée', 'notes' => trim(($ret->notes ? $ret->notes . "\n" : '') . '[Annulé le ' . now()->format('d/m/Y') . ($reason ? ' : ' . $reason : '') . ']')]);
            return $ret;
        });
    }

    /* ═══════════════════════════ AVOIRS ═══════════════════════════
     * Convention (comme les ventes) : les lignes et totaux d'un avoir sont NÉGATIFS ; valider un avoir ajoute son TTC
     * (négatif) au solde du fournisseur, donc le solde diminue. Brouillon = aucun effet.
     */

    /** Quantité déjà couverte par des avoirs non annulés, par « source|article ». */
    private function creditedMap(string $type, array $sourceIds, ?int $excludeNoteId): array
    {
        $col = $type === 'from_return' ? 'purchase_return_id' : 'purchase_invoice_id';
        $q = PurchaseNoteLine::query()
            ->join('purchase_notes', 'purchase_notes.id', '=', 'purchase_note_lines.purchase_note_id')
            ->whereIn("purchase_note_lines.$col", $sourceIds)
            ->where('purchase_notes.status', '!=', 'annulée');
        if ($excludeNoteId) $q->where('purchase_notes.id', '!=', $excludeNoteId);
        $out = [];
        foreach ($q->selectRaw("purchase_note_lines.$col as sid, purchase_note_lines.article_code as code, SUM(ABS(purchase_note_lines.quantity)) as qty")
                   ->groupBy("purchase_note_lines.$col", 'purchase_note_lines.article_code')->get() as $r) {
            $out[$r->sid . '|' . $r->code] = (float) $r->qty;
        }
        return $out;
    }

    /** Lignes sources créditables : [ ['source_id','source_numdoc','code','name','qty','credited','available','pu','remise','tva'] ] */
    public function creditableLines(string $type, array $sourceIds, ?int $excludeNoteId = null): array
    {
        $credited = $this->creditedMap($type, $sourceIds, $excludeNoteId);
        $rows = [];
        if ($type === 'from_return') {
            foreach (PurchaseReturn::with('lines')->whereIn('id', $sourceIds)->get() as $r) {
                foreach ($r->lines as $l) $rows[] = [$r->id, $r->numdoc, $l->article_code, abs((float) $l->returned_quantity), (float) $l->unit_price_ht, (float) $l->remise, $l->tva !== null ? (float) $l->tva : (float) ($r->tva_rate ?? 0)];
            }
        } else {
            foreach (PurchaseInvoice::with('lines')->whereIn('id', $sourceIds)->get() as $inv) {
                foreach ($inv->lines as $l) {
                    if (!$l->article_code || $l->purchase_return_id || (float) $l->quantity < 0) continue;   // les lignes de retour d'une facture groupée ne se recréditent pas
                    $rows[] = [$inv->id, $inv->numdoc, $l->article_code, abs((float) $l->quantity), (float) $l->unit_price_ht, (float) $l->remise, $l->tva !== null ? (float) $l->tva : (float) ($inv->tva_rate ?? 0)];
                }
            }
        }
        $merged = [];
        foreach ($rows as [$sid, $num, $code, $qty, $pu, $rem, $tva]) {
            $k = $sid . '|' . $code;
            if (!isset($merged[$k])) {
                $merged[$k] = ['source_id' => $sid, 'source_numdoc' => $num, 'code' => $code, 'name' => optional(Item::where('code', $code)->first())->name,
                               'qty' => 0.0, 'credited' => $credited[$k] ?? 0.0, 'available' => 0.0, 'pu' => $pu, 'remise' => $rem, 'tva' => $tva];
            }
            $merged[$k]['qty'] += $qty;
        }
        foreach ($merged as $k => $m) $merged[$k]['available'] = max(0.0, round($m['qty'] - $m['credited'], 2));
        return array_values($merged);
    }

    /** Valide les lignes demandées contre les sources et calcule les montants (négatifs). */
    private function buildNoteLines(string $type, array $sourceIds, array $rawLines, ?int $excludeNoteId): array
    {
        $avail = [];
        foreach ($this->creditableLines($type, $sourceIds, $excludeNoteId) as $c) $avail[$c['source_id'] . '|' . $c['code']] = $c;
        $col = $type === 'from_return' ? 'purchase_return_id' : 'purchase_invoice_id';
        $used = []; $out = [];
        foreach ($rawLines as $i => $r) {
            $qty = abs($this->num($r['quantity'] ?? 0));
            if ($qty == 0.0) continue;
            $sid = (int) ($r[$col] ?? 0); $code = (string) ($r['article_code'] ?? '');
            $k = $sid . '|' . $code;
            if (!isset($avail[$k])) throw new PurchaseException('Ligne ' . ((int) $i + 1) . " : l'article {$code} ne figure pas dans le document source sélectionné.");
            $used[$k] = ($used[$k] ?? 0) + $qty;
            if ($used[$k] > $avail[$k]['available'] + 0.00001) {
                throw new PurchaseException("Article {$code} ({$avail[$k]['source_numdoc']}) : quantité trop élevée (source {$avail[$k]['qty']}, déjà couvert par d'autres avoirs {$avail[$k]['credited']}, maximum {$avail[$k]['available']}).");
            }
            $pu     = (isset($r['unit_price_ht']) && $r['unit_price_ht'] !== '') ? $this->num($r['unit_price_ht']) : $avail[$k]['pu'];
            $remise = (isset($r['remise']) && $r['remise'] !== '') ? $this->num($r['remise']) : $avail[$k]['remise'];
            if ($pu < 0) throw new PurchaseException("Article {$code} : prix invalide.");
            if ($remise < 0 || $remise > 100) throw new PurchaseException("Article {$code} : la remise doit être comprise entre 0 et 100 %.");
            $tva = $avail[$k]['tva'];
            $ht  = round(-$qty * $pu * (1 - $remise / 100), 2);
            $out[] = ['source_id' => $sid, 'code' => $code, 'qty' => -$qty, 'pu' => $pu, 'remise' => $remise, 'tva' => $tva,
                      'total_ht' => $ht, 'total_ttc' => round($ht * (1 + $tva / 100), 2), 'description' => $r['description'] ?? null];
        }
        if (!$out) throw new PurchaseException("Saisissez au moins une ligne avec une quantité.");
        return $out;
    }

    private function noteTotals(array $lines): array
    {
        $ht = 0.0; $ttc = 0.0; $by = [];
        foreach ($lines as $l) { $ht += $l['total_ht']; $ttc += $l['total_ttc']; $by[(string) $l['tva']] = ($by[(string) $l['tva']] ?? 0) + abs($l['total_ht']); }
        arsort($by);
        return ['ht' => round($ht, 2), 'ttc' => round($ttc, 2), 'tva_rate' => $by ? (float) array_key_first($by) : 0.0];
    }

    private function writeNoteLines(PurchaseNote $note, string $type, array $lines): void
    {
        foreach ($lines as $l) {
            PurchaseNoteLine::create([
                'purchase_note_id' => $note->id, 'article_code' => $l['code'],
                'purchase_return_id' => $type === 'from_return' ? $l['source_id'] : null,
                'purchase_invoice_id' => $type === 'from_invoice' ? $l['source_id'] : null,
                'quantity' => $l['qty'], 'unit_price_ht' => $l['pu'], 'remise' => $l['remise'],
                'total_ligne_ht' => $l['total_ht'], 'tva' => $l['tva'], 'prix_ttc' => $l['total_ttc'], 'description' => $l['description'],
            ]);
        }
    }

    /**
     * Avoir fournisseur depuis des RETOURS non facturés (type from_return) ou depuis des FACTURES validées (from_invoice).
     * Header : note_date, notes, return_goods (avoir sur facture : la marchandise ressort du stock à la validation).
     * Les retours utilisés sont réservés dès le brouillon (invoiced = 1) et libérés à l'annulation.
     */
    public function createNote(Supplier $supplier, string $type, array $header, array $sourceIds, array $rawLines, bool $validate = false): PurchaseNote
    {
        if (!in_array($type, ['from_return', 'from_invoice'], true)) throw new PurchaseException('Type d\'avoir inconnu.');
        return DB::transaction(function () use ($supplier, $type, $header, $sourceIds, $rawLines, $validate) {
            $sourceIds = array_values(array_unique(array_map('intval', $sourceIds)));
            if (!$sourceIds) throw new PurchaseException($type === 'from_return' ? 'Sélectionnez au moins un retour.' : 'Sélectionnez au moins une facture.');
            $this->assertNoteSources($supplier, $type, $sourceIds, null);

            $lines  = $this->buildNoteLines($type, $sourceIds, $rawLines, null);
            $totals = $this->noteTotals($lines);

            $note = PurchaseNote::create([
                'supplier_id' => $supplier->id, 'numdoc' => $this->nextNumber('avoir_achat'), 'note_date' => $header['note_date'] ?? now()->toDateString(),
                'status' => 'brouillon', 'type' => $type, 'total_ht' => $totals['ht'], 'total_ttc' => $totals['ttc'], 'tva_rate' => $totals['tva_rate'],
                'notes' => $header['notes'] ?? null, 'purchase_return_id' => null, 'purchase_invoice_id' => null,
                'return_goods' => $type === 'from_invoice' && !empty($header['return_goods']),
            ]);
            $this->writeNoteLines($note, $type, $lines);

            if ($type === 'from_return') {
                PurchaseReturn::whereIn('id', array_unique(array_column($lines, 'source_id')))->update(['invoiced' => 1]);
            }
            if ($validate) $this->applyNoteValidation($note);

            return $note->fresh('lines');
        });
    }

    private function assertNoteSources(Supplier $supplier, string $type, array $ids, ?int $noteId): void
    {
        if ($type === 'from_return') {
            $found = PurchaseReturn::whereIn('id', $ids)->lockForUpdate()->get();
            if ($found->count() !== count($ids)) throw new PurchaseException('Retour introuvable.');
            $mine = $noteId ? PurchaseNoteLine::where('purchase_note_id', $noteId)->pluck('purchase_return_id')->filter()->unique()->all() : [];
            foreach ($found as $r) {
                if ($r->status === 'annulée') throw new PurchaseException("Le retour {$r->numdoc} est annulé.");
                if ((int) $r->supplier_id !== (int) $supplier->id) throw new PurchaseException("Le retour {$r->numdoc} n'appartient pas à ce fournisseur.");
                if ($r->invoiced && !in_array($r->id, $mine)) throw new PurchaseException("Le retour {$r->numdoc} est déjà couvert par un avoir ou une facture.");
            }
        } else {
            $found = PurchaseInvoice::whereIn('id', $ids)->lockForUpdate()->get();
            if ($found->count() !== count($ids)) throw new PurchaseException('Facture introuvable.');
            foreach ($found as $f) {
                if ($f->status !== 'validée') throw new PurchaseException("La facture {$f->numdoc} doit être validée pour recevoir un avoir.");
                if ((int) $f->supplier_id !== (int) $supplier->id) throw new PurchaseException("La facture {$f->numdoc} n'appartient pas à ce fournisseur.");
            }
        }
    }

    /** Effets de la validation : solde du fournisseur + (avoir sur facture) retour automatique et sortie de stock. */
    private function applyNoteValidation(PurchaseNote $note): void
    {
        $supplier = Supplier::findOrFail($note->supplier_id);
        $note->update(['status' => 'validée']);
        $this->addSolde($supplier->id, (float) $note->total_ttc);

        if ($note->type !== 'from_invoice' || !$note->return_goods) return;

        // une marchandise ressort par commande d'origine (pour que « retournable » reste juste côté commande)
        $groups = [];
        foreach (PurchaseNoteLine::where('purchase_note_id', $note->id)->get() as $l) {
            $orderId = PurchaseInvoiceLine::where('purchase_invoice_id', $l->purchase_invoice_id)->where('article_code', $l->article_code)->whereNotNull('purchase_order_id')->value('purchase_order_id');
            $groups[(int) $orderId][] = $l;
        }
        foreach ($groups as $orderId => $lines) {
            $order   = $orderId ? PurchaseOrder::find($orderId) : null;
            $storeId = (int) ($order->store_id ?? 1);
            $numdoc  = $this->nextNumber('retour_achat');
            $ret = PurchaseReturn::create([
                'purchase_order_id' => $orderId ?: null, 'supplier_id' => $supplier->id, 'numdoc' => $numdoc, 'return_date' => $note->note_date,
                'type' => 'partiel', 'total_ht' => 0, 'total_ttc' => 0, 'tva_rate' => $note->tva_rate, 'invoiced' => 1, 'status' => 'validée',
                'notes' => 'Retour généré pour l\'avoir ' . $note->numdoc, 'purchase_note_id' => $note->id,
            ]);
            $ht = 0.0; $ttc = 0.0;
            foreach ($lines as $l) {
                $q = abs((float) $l->quantity); $lineHt = abs((float) $l->total_ligne_ht); $lineTtc = abs((float) $l->prix_ttc);
                $ht += $lineHt; $ttc += $lineTtc;
                PurchaseReturnLine::create(['purchase_return_id' => $ret->id, 'article_code' => $l->article_code, 'returned_quantity' => $q,
                    'unit_price_ht' => $l->unit_price_ht, 'remise' => $l->remise, 'total_ligne_ht' => $lineHt, 'tva' => $l->tva]);
                $item = Item::where('code', $l->article_code)->first();
                if ($item) {
                    $this->moveStock($item, $storeId, -$q, 'retour_achat', (float) $l->unit_price_ht * (1 - (float) $l->remise / 100), $supplier->name, $numdoc, 'Retour généré pour l\'avoir ' . $note->numdoc, null);
                }
            }
            $ret->update(['total_ht' => round($ht, 2), 'total_ttc' => round($ttc, 2)]);
        }
    }

    /** Valide un brouillon d'avoir tel quel. */
    public function validateNote(int $noteId): PurchaseNote
    {
        return DB::transaction(function () use ($noteId) {
            $note = PurchaseNote::whereKey($noteId)->lockForUpdate()->firstOrFail();
            if ($note->status !== 'brouillon') throw new PurchaseException("L'avoir {$note->numdoc} n'est pas un brouillon.");
            $supplier = Supplier::findOrFail($note->supplier_id);
            if ($note->type === 'from_invoice') {
                $ids = PurchaseNoteLine::where('purchase_note_id', $note->id)->pluck('purchase_invoice_id')->filter()->unique()->all();
                $this->assertNoteSources($supplier, 'from_invoice', $ids, $note->id);
            }
            $this->applyNoteValidation($note);
            return $note->fresh('lines');
        });
    }

    /** Modification d'un BROUILLON : lignes remplacées (mêmes documents sources), quantités revérifiées. */
    public function updateDraftNote(int $noteId, array $header, array $rawLines, bool $validate = false): PurchaseNote
    {
        return DB::transaction(function () use ($noteId, $header, $rawLines, $validate) {
            $note = PurchaseNote::whereKey($noteId)->lockForUpdate()->firstOrFail();
            if ($note->status !== 'brouillon') throw new PurchaseException("L'avoir {$note->numdoc} n'est plus un brouillon : il n'est plus modifiable.");
            $type = $note->type;
            $col  = $type === 'from_return' ? 'purchase_return_id' : 'purchase_invoice_id';
            $sourceIds = PurchaseNoteLine::where('purchase_note_id', $note->id)->pluck($col)->filter()->unique()->values()->all();
            if (!$sourceIds) throw new PurchaseException("L'avoir {$note->numdoc} n'a pas de document source : créez-en un nouveau.");

            $lines  = $this->buildNoteLines($type, $sourceIds, $rawLines, $note->id);
            $totals = $this->noteTotals($lines);

            PurchaseNoteLine::where('purchase_note_id', $note->id)->delete();
            $this->writeNoteLines($note, $type, $lines);
            $note->update([
                'note_date' => $header['note_date'] ?? $note->note_date, 'notes' => $header['notes'] ?? null,
                'total_ht' => $totals['ht'], 'total_ttc' => $totals['ttc'], 'tva_rate' => $totals['tva_rate'],
                'return_goods' => $type === 'from_invoice' ? !empty($header['return_goods']) : false,
            ]);
            if ($validate) {
                $this->assertNoteSources(Supplier::findOrFail($note->supplier_id), $type, $sourceIds, $note->id);
                $this->applyNoteValidation($note);
            }
            return $note->fresh('lines');
        });
    }

    /**
     * Annule un avoir (brouillon ou validé) : solde remis, retours réservés libérés, retours automatiques annulés
     * (la marchandise revient en stock). Refusé s'il existe des règlements sur l'avoir.
     */
    public function cancelNote(int $noteId, ?string $reason = null): PurchaseNote
    {
        return DB::transaction(function () use ($noteId, $reason) {
            $note = PurchaseNote::whereKey($noteId)->lockForUpdate()->firstOrFail();
            if ($note->status === 'annulée') throw new PurchaseException("L'avoir {$note->numdoc} est déjà annulé.");
            if ($note->payments()->exists()) throw new PurchaseException("L'avoir {$note->numdoc} a des règlements : annulez d'abord les règlements.");

            if ($note->status === 'validée') {
                $this->addSolde($note->supplier_id, -(float) $note->total_ttc);
                $supplier = Supplier::find($note->supplier_id);
                foreach (PurchaseReturn::where('purchase_note_id', $note->id)->where('status', '!=', 'annulée')->get() as $ret) {
                    $order = $ret->purchase_order_id ? PurchaseOrder::find($ret->purchase_order_id) : null;
                    $storeId = (int) ($order->store_id ?? 1);
                    foreach (PurchaseReturnLine::where('purchase_return_id', $ret->id)->get() as $l) {
                        $item = Item::where('code', $l->article_code)->first();
                        if ($item) $this->moveStock($item, $storeId, abs((float) $l->returned_quantity), 'annulation_retour_achat', (float) $l->unit_price_ht * (1 - (float) $l->remise / 100), $supplier->name ?? '', $ret->numdoc, 'Annulation de l\'avoir ' . $note->numdoc, null);
                    }
                    $ret->update(['status' => 'annulée']);
                }
            }
            if ($note->type === 'from_return') {
                $ids = PurchaseNoteLine::where('purchase_note_id', $note->id)->pluck('purchase_return_id')->filter()->unique()->all();
                if ($ids) PurchaseReturn::whereIn('id', $ids)->update(['invoiced' => 0]);
            }
            $note->update(['status' => 'annulée', 'paid' => 0, 'notes' => trim(($note->notes ? $note->notes . "\n" : '') . '[Annulé le ' . now()->format('d/m/Y') . ($reason ? ' : ' . $reason : '') . ']')]);
            return $note;
        });
    }

    /** Un avoir non annulé couvre-t-il ce retour / cette facture ? (lien porté par les lignes de l'avoir, ou par l'en-tête pour les anciens avoirs) */
    private function hasActiveNote(string $col, int $id): bool
    {
        $viaLines = PurchaseNoteLine::query()
            ->join('purchase_notes', 'purchase_notes.id', '=', 'purchase_note_lines.purchase_note_id')
            ->where("purchase_note_lines.$col", $id)->where('purchase_notes.status', '!=', 'annulée')->exists();
        return $viaLines || PurchaseNote::where($col, $id)->where('status', '!=', 'annulée')->exists();
    }

    private function assertEditable(Reception $rec): void
    {
        if ($rec->status === 'annulée') throw new PurchaseException("La réception {$rec->numdoc} est annulée.");
        if ($rec->invoiced)             throw new PurchaseException("La réception {$rec->numdoc} est déjà facturée : rectifiez d'abord la facture.");
        if (strpos((string) $rec->numdoc, 'REC-H') === 0 || !$rec->stock_applied) throw new PurchaseException("La réception {$rec->numdoc} est une réception historique (stock déjà pris en compte avant la migration) : elle ne peut pas être rectifiée ici.");
    }
}
