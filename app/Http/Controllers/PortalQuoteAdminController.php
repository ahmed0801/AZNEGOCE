<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\Souche;
use App\Services\B2bPricingService;
use App\Services\PortalQuotePdf;
use App\Models\PortalQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Boîte de réception « Demandes de devis » côté ERP (vendeurs et administrateurs). */
class PortalQuoteAdminController extends Controller
{
    const STATUSES = ['new', 'processing', 'negotiating', 'answered', 'accepted', 'declined', 'cancelled'];

    public function index()
    {
        return view('b2b_quotes', ['todo' => PortalQuote::whereIn('status', ['new', 'processing', 'negotiating'])->count()]);
    }

    private function row($q)
    {
        return [
            'id' => $q->id, 'number' => $q->number, 'status' => $q->status, 'status_label' => $q->status_label,
            'customer' => optional($q->customer)->name, 'customer_code' => optional($q->customer)->code, 'user' => optional($q->user)->name,
            'vehicle' => $q->vehicle_text, 'lines' => $q->lines_count, 'total_ttc' => (float) $q->total_ttc,
            'created' => $q->created_at->format('d/m/Y H:i'), 'ago' => $q->created_at->diffForHumans(),
        ];
    }

    public function data(Request $request)
    {
        $status = $request->query('status', 'todo');
        $counts = DB::table('portal_quotes')->selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status')->all();
        $qb = PortalQuote::with(['customer:id,name,code', 'user:id,name'])->orderByDesc('id');
        if ($status === 'todo') $qb->whereIn('status', ['new', 'processing', 'negotiating']);
        elseif (in_array($status, self::STATUSES, true)) $qb->where('status', $status);
        if ($s = trim((string) $request->query('q', ''))) {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $s) . '%';
            $qb->where(function ($w) use ($like) {
                $w->where('number', 'like', $like)->orWhere('vehicle_text', 'like', $like)->orWhere('vin', 'like', $like)
                  ->orWhereHas('customer', function ($c) use ($like) { $c->where('name', 'like', $like)->orWhere('code', 'like', $like); });
            });
        }
        $p = $qb->paginate(20);
        $n = function ($k) use ($counts) { return (int) ($counts[$k] ?? 0); };
        return response()->json([
            'rows' => collect($p->items())->map(function ($q) { return $this->row($q); })->all(),
            'page' => $p->currentPage(), 'last' => $p->lastPage(), 'total' => $p->total(),
            'counts' => ['todo' => $n('new') + $n('processing') + $n('negotiating'), 'answered' => $n('answered'), 'accepted' => $n('accepted'), 'declined' => $n('declined'), 'cancelled' => $n('cancelled')],
        ]);
    }

    public function show($id)
    {
        $q = PortalQuote::with(['lines', 'customer', 'user', 'events'])->findOrFail($id);
        $c = $q->customer;
        $tvaDefault = (float) (optional(optional($c)->tvaGroup)->rate ?: 0);
        return response()->json($this->row($q) + [
            'vin' => $q->vin, 'note' => $q->note, 'vendor_message' => $q->vendor_message, 'internal_note' => $q->internal_note,
            'customer_phone' => $c ? ($c->phone1 ?: $c->phone2) : null, 'customer_city' => $c ? $c->city : null, 'customer_id' => $q->customer_id,
            'valid_until' => $q->valid_until ? $q->valid_until->format('Y-m-d') : null, 'tva_default' => $tvaDefault,
            'total_ht' => (float) $q->total_ht, 'total_tva' => (float) $q->total_tva,
            'decided' => $q->decided_at ? $q->decided_at->format('d/m/Y H:i') : null,
            'editable' => in_array($q->status, ['new', 'processing', 'negotiating', 'answered'], true), 'round' => (int) $q->round,
            'events' => $q->events->map(function ($e) { return ['actor' => $e->actor, 'label' => \App\Models\PortalQuoteEvent::LABELS[$e->type] ?? $e->type, 'type' => $e->type, 'message' => $e->message, 'who' => $e->who, 'at' => $e->created_at ? $e->created_at->format('d/m/Y H:i') : ''];})->all(),
            'erp_id' => $q->sales_order_id, 'erp_numdoc' => $q->erp_numdoc, 'erp_url' => $q->sales_order_id ? route('sales.edit', $q->sales_order_id) : null,
            'can_pdf' => $q->status === 'answered' || $q->status === 'accepted' || $q->lines->whereNotNull('unit_ht')->count() > 0,
            'plate' => $q->plate, 'offered_ttc' => $q->offered_ttc !== null ? (float) $q->offered_ttc : null, 'total_ttc' => (float) $q->total_ttc,
            'files' => \App\Services\PortalQuoteFiles::listFor($q)->map(function ($f) use ($q) {
                return ['id' => $f->id, 'name' => $f->name, 'by' => $f->by, 'who' => $f->who, 'mime' => $f->mime, 'size' => (int) $f->size, 'image' => $f->is_image,
                        'url' => route('b2b.quotes.file', ['quote' => $q->id, 'file' => $f->id]), 'at' => $f->created_at ? $f->created_at->format('d/m H:i') : ''];
            })->all(),
            'items' => $q->lines->map(function ($l) use ($q) {
                $auto = null;
                if ($l->unit_ht === null && !$l->item_id && $l->reference && in_array($q->status, ['new', 'processing'], true)) {
                    $auto = $this->autoMatch($l->reference, $q->customer, (int) $l->qty);       // prix proposé (à valider par le vendeur)
                }
                return ['auto' => $auto, 'id' => $l->id, 'reference' => $l->reference, 'designation' => $l->designation, 'brand' => $l->brand, 'qty' => (int) $l->qty,
                        'unit_ht' => $l->unit_ht !== null ? (float) $l->unit_ht : null, 'list_ht' => $l->list_ht !== null ? (float) $l->list_ht : ($l->unit_ht !== null ? (float) $l->unit_ht : null), 'remise' => (float) $l->remise, 'tva_rate' => (float) $l->tva_rate, 'availability' => $l->availability,
                        'delay_text' => $l->delay_text, 'vendor_ref' => $l->vendor_ref, 'item_id' => $l->item_id,
                        'target_price' => $l->target_price !== null ? (float) $l->target_price : null, 'client_comment' => $l->client_comment,
                        'accepted' => $l->accepted === null ? null : ((int) $l->accepted === 1)];
            })->all(),
        ]);
    }

    /** Enregistre le travail du vendeur. send=1 : envoie la réponse au client ; sinon simple brouillon (statut « en cours »). */
    public function update(Request $request, $id)
    {
        $v = $request->validate([
            'send' => 'nullable|boolean', 'vendor_message' => 'nullable|string|max:1000', 'internal_note' => 'nullable|string|max:1000',
            'valid_days' => 'nullable|integer|min:0|max:365',
            'lines' => 'required|array|min:1|max:60',
            'lines.*.id' => 'nullable|integer', 'lines.*.removed' => 'nullable|boolean',
            'lines.*.reference' => 'nullable|string|max:80', 'lines.*.designation' => 'required|string|max:255', 'lines.*.brand' => 'nullable|string|max:120',
            'lines.*.qty' => 'required|integer|min:1|max:9999', 'lines.*.unit_ht' => 'nullable|numeric|min:0|max:10000000', 'lines.*.list_ht' => 'nullable|numeric|min:0|max:10000000', 'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.tva_rate' => 'nullable|numeric|min:0|max:100', 'lines.*.availability' => 'nullable|in:stock,delay,unavailable',
            'lines.*.delay_text' => 'nullable|string|max:60', 'lines.*.vendor_ref' => 'nullable|string|max:80', 'lines.*.item_id' => 'nullable|integer',
        ]);
        $send = !empty($v['send']);
        try {
            $res = DB::transaction(function () use ($v, $id, $send) {
                $q = PortalQuote::lockForUpdate()->findOrFail($id);
                if (!in_array($q->status, ['new', 'processing', 'negotiating', 'answered'], true)) return ['ok' => false, 'message' => 'Cette demande est clôturée.', 'code' => 409];
                $keep = []; $ht = 0.0; $tva = 0.0; $count = 0; $priced = 0;
                foreach ($v['lines'] as $in) {
                    if (!empty($in['removed'])) continue;
                    // tarif HT + remise % (comme l'ERP) ; ancien champ unit_ht accepté = tarif sans remise
                    $list = (isset($in['list_ht']) && $in['list_ht'] !== '' && $in['list_ht'] !== null) ? round((float) $in['list_ht'], 4)
                          : ((isset($in['unit_ht']) && $in['unit_ht'] !== '' && $in['unit_ht'] !== null) ? round((float) $in['unit_ht'], 4) : null);
                    $rem = $list !== null ? round((float) ($in['remise'] ?? 0), 2) : 0.0;
                    $unit = $list !== null ? round($list * (1 - $rem / 100), 4) : null;                 // prix net unitaire
                    $av = $in['availability'] ?? null;
                    $rate = (float) ($in['tva_rate'] ?? 0);
                    $qty = (int) $in['qty'];
                    $lineHt = ($list !== null && $av !== 'unavailable') ? round($list * $qty * (1 - $rem / 100), 2) : 0.0;
                    if ($lineHt > 0 || ($unit !== null && $av !== 'unavailable')) $priced++;
                    $ht += $lineHt; $tva += round($lineHt * $rate / 100, 2); $count++;
                    $data = ['reference' => $in['reference'] ?? null, 'designation' => $in['designation'], 'brand' => $in['brand'] ?? null, 'qty' => $qty,
                             'unit_ht' => $unit, 'list_ht' => $list, 'remise' => $rem, 'tva_rate' => $rate, 'line_ht' => $lineHt, 'availability' => $av ?: null,
                             'delay_text' => ($av === 'delay') ? ($in['delay_text'] ?? null) : null, 'vendor_ref' => $in['vendor_ref'] ?? null,
                             'item_id' => !empty($in['item_id']) && Item::where('id', (int) $in['item_id'])->exists() ? (int) $in['item_id'] : null,
                             'accepted' => null];
                    $line = !empty($in['id']) ? $q->lines()->where('id', (int) $in['id'])->first() : null;   // seulement les lignes DE CETTE demande
                    if ($line) { $line->update($data); } else { $line = $q->lines()->create($data); }
                    $keep[] = $line->id;
                }
                if ($count === 0) return ['ok' => false, 'message' => 'Gardez au moins une ligne.', 'code' => 422];
                if ($send && $priced === 0) return ['ok' => false, 'message' => 'Renseignez au moins un prix avant d\'envoyer le devis (ou refusez la demande).', 'code' => 422];
                $q->lines()->whereNotIn('id', $keep)->delete();
                $q->total_ht = round($ht, 2); $q->total_tva = round($tva, 2); $q->total_ttc = round($ht + $tva, 2); $q->lines_count = $count;
                $q->vendor_message = trim((string) ($v['vendor_message'] ?? '')) ?: null;
                $q->internal_note = trim((string) ($v['internal_note'] ?? '')) ?: null;
                if ($send) {
                    $days = isset($v['valid_days']) ? (int) $v['valid_days'] : 15;
                    if (in_array($q->status, ['negotiating', 'answered'], true)) $q->round = (int) $q->round + 1;
                    $q->offered_ht = null; $q->offered_ttc = null;                 // nouvelle proposition : les refus de lignes précédents sont remis à zéro
                    $q->status = 'answered'; $q->answered_at = now(); $q->answered_by = Auth::id();
                    $q->valid_until = $days > 0 ? now()->addDays($days)->toDateString() : null;
                } elseif ($q->status === 'new') {
                    $q->status = 'processing';
                }
                $q->save();
                if ($send) {
                    $q->lines()->update(['target_price' => null, 'client_comment' => null]);       // la négociation est traitée
                    $q->log('vendor', 'answered', $q->vendor_message, optional(Auth::user())->name);
                }
                return ['ok' => true, 'quote' => $q];
            });
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal quote update failed', ['id' => $id, 'e' => $e->getMessage()]);
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
        $code = $res['code'] ?? 200; unset($res['code']);
        if (!empty($res['ok'])) {
            $qq = $res['quote'];
            $res['number'] = $qq->number; $res['customer'] = optional($qq->customer)->name;
            if ($send) $res['mailed'] = $this->mailClient($qq);
        }
        unset($res['quote']);
        return response()->json($res, $code);
    }

    // ───────────── recherche d'article + prix proposé ─────────────

    private function norm($s)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $s));
    }

    /** Fiche proposée au vendeur : article, stock total, prix net HT du client (service de prix du portail), TVA. */
    private function itemPayload(Item $it, $customer, $qty = 1)
    {
        $stock = (float) DB::table('stocks')->where('item_id', $it->id)->sum('quantity');
        $price = null; $tva = null; $list = null; $rem = 0.0; $guard = false;
        try {
            if ($customer) {
                $p = (new B2bPricingService())->price($it, $customer);
                // Comme dans l'ERP : tarif de vente + remise prédéfinie du groupe (pas de relèvement du garde-fou de marge, le vendeur garde la main)
                $list = (float) $p['list_ht']; $rem = (float) $p['rate']; $tva = (float) $p['tva_rate'];
                $price = round($list * (1 - $rem / 100), 2);
                if (!empty($p['guard']) || !empty($p['below_margin'])) $guard = true;
            }
        } catch (\Throwable $e) { }
        return ['item_id' => $it->id, 'code' => $it->code, 'name' => $it->name, 'brand' => optional($it->brand)->name, 'stock' => $stock,
                'unit_ht' => $price, 'list_ht' => $list, 'remise' => $rem, 'below_margin' => $guard, 'promo' => (isset($p) && !empty($p['promo'])) ? $p['promo']['name'] : null, 'tva_rate' => $tva, 'availability' => $stock >= max(1, $qty) ? 'stock' : null];
    }

    /** Correspondance EXACTE (code, code-barres, réf. TecDoc, code fournisseur) pour pré-remplir. Plusieurs candidats = pas de pré-remplissage. */
    private function autoMatch($ref, $customer, $qty)
    {
        $ref = trim((string) $ref); $n = $this->norm($ref);
        if ($ref === '' || strlen($n) < 3) return null;
        $vals = array_values(array_unique([$ref, $n]));
        $rows = Item::with('brand')->where(function ($w) use ($vals) {
            $w->whereIn('code', $vals)->orWhereIn('barcode', $vals)->orWhereIn('Ref_TecDoc', $vals)->orWhereIn('codefournisseur', $vals);
        })->limit(2)->get();
        if ($rows->count() !== 1) return null;
        return $this->itemPayload($rows->first(), $customer, $qty);
    }

    public function itemsSearch(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        if (mb_strlen($term) < 2) return response()->json(['rows' => []]);
        $customer = Customer::with(['tvaGroup', 'discountGroup'])->find((int) $request->query('customer_id'));
        $qty = max(1, (int) $request->query('qty', 1));
        $n = $this->norm($term);
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
        $qb = Item::with('brand')->where(function ($w) use ($term, $n, $like) {
            $w->where('code', $term)->orWhere('code', $n)->orWhere('barcode', $term)->orWhere('Ref_TecDoc', $term)->orWhere('codefournisseur', $term)
              ->orWhere('code', 'like', $like . '%')->orWhere('name', 'like', '%' . $like . '%');
        })->orderByRaw('CASE WHEN code = ? THEN 0 WHEN code LIKE ? THEN 1 ELSE 2 END', [$term, $like . '%'])->limit(12);
        $rows = $qb->get()->map(function ($it) use ($customer, $qty) { return $this->itemPayload($it, $customer, $qty); })->all();
        return response()->json(['rows' => $rows]);
    }

    // ───────────── PDF + devis ERP ─────────────

    public function pdf(Request $request, $id)
    {
        $q = PortalQuote::findOrFail($id);
        list($pdf, $name) = (new PortalQuotePdf())->make($q);
        return $request->boolean('inline') ? $pdf->stream($name) : $pdf->download($name);
    }

    /** Pièce jointe d'un devis (session ERP). */
    public function file($id, $fileId, Request $request)
    {
        $q = PortalQuote::findOrFail($id);
        return \App\Services\PortalQuoteFiles::response($q, $fileId, $request->boolean('dl'));
    }

    /** Le vendeur joint des photos / documents (fiche technique, photo de la pièce proposée…) visibles par le client. */
    public function uploadFiles(Request $request, $id)
    {
        $request->validate(\App\Services\PortalQuoteFiles::rules(), \App\Services\PortalQuoteFiles::messages());
        $q = PortalQuote::findOrFail($id);
        $n = \App\Services\PortalQuoteFiles::store($q, $request->file('files'), 'vendor', optional(Auth::user())->name);
        if (!$n) return response()->json(['ok' => false, 'message' => 'Aucun fichier valide (photos JPG/PNG/WebP ou PDF, 8 Mo maximum).'], 422);
        $q->log('vendor', 'file', $n . ' pièce(s) jointe(s) ajoutée(s)', optional(Auth::user())->name);
        return response()->json(['ok' => true, 'message' => $n . ' fichier(s) ajouté(s).']);
    }

    /** Supprime une pièce jointe ajoutée par un vendeur (jamais celles du client). */
    public function deleteFile($id, $fileId)
    {
        $f = \App\Models\PortalQuoteFile::where('quote_id', $id)->where('id', $fileId)->where('by', 'vendor')->firstOrFail();
        try { \Illuminate\Support\Facades\Storage::disk('local')->delete($f->path); } catch (\Throwable $e) { }
        $f->delete();
        return response()->json(['ok' => true, 'message' => 'Fichier supprimé.']);
    }

    /** Crée (ou met à jour) le devis ERP — commande vente au statut « Devis » — à partir des lignes liées à un article du catalogue. */
    public function toErp($id)
    {
        try {
            $res = DB::transaction(function () use ($id) {
                $q = PortalQuote::with('lines')->lockForUpdate()->findOrFail($id);
                $customer = Customer::with('tvaGroup')->findOrFail($q->customer_id);
                $tva = (float) ($customer->tvaGroup->rate ?? 0);
                $linked = $q->lines->filter(function ($l) { return $l->item_id && $l->unit_ht !== null && $l->availability !== 'unavailable' && ($l->accepted === null || (int) $l->accepted === 1); });   // lignes refusées par le client exclues
                if ($linked->isEmpty()) return ['ok' => false, 'message' => "Aucune ligne chiffrée n'est liée à un article du catalogue : utilisez la loupe de chaque ligne pour choisir l'article, puis enregistrez."];

                $so = $q->sales_order_id ? SalesOrder::lockForUpdate()->find($q->sales_order_id) : null;
                if ($so && $so->status !== 'Devis' && $so->status !== 'brouillon') {
                    return ['ok' => false, 'message' => 'Le devis ' . $q->erp_numdoc . " a déjà été converti dans l'ERP : modifiez-le là-bas."];
                }
                $skipped = $q->lines->diff($linked)->filter(function ($l) { return $l->accepted === null || (int) $l->accepted === 1; });
                $notes = 'Demande portail ' . $q->number . ($q->vehicle_text ? ' - Véhicule : ' . $q->vehicle_text : '') . ($q->vin ? ' (VIN ' . $q->vin . ')' : '');
                if ($skipped->count()) $notes .= ' - À saisir : ' . $skipped->map(function ($l) { return $l->qty . ' x ' . $l->designation . ($l->reference ? ' [' . $l->reference . ']' : '') . ($l->availability === 'unavailable' ? ' (indisponible)' : ''); })->implode(' ; ');

                if (!$so) {
                    $souche = Souche::where('type', 'devis')->lockForUpdate()->first();
                    if (!$souche) return ['ok' => false, 'message' => "Souche « devis » manquante dans l'ERP."];
                    $numdoc = null;
                    for ($i = 0; $i < 5; $i++) {
                        $cand = ($souche->prefix ?? '') . ($souche->suffix ?? '') . str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
                        if (!SalesOrder::where('numdoc', $cand)->exists()) { $numdoc = $cand; break; }
                        $souche->last_number += 1; $souche->save();
                    }
                    if (!$numdoc) return ['ok' => false, 'message' => 'Impossible de générer un numéro de devis unique.'];
                    $so = SalesOrder::create([
                        'customer_id' => $customer->id, 'order_date' => now()->toDateString(), 'numclient' => $customer->code, 'vehicle_id' => $q->vehicle_id,
                        'status' => 'Devis', 'total_ht' => 0, 'total_ttc' => 0, 'notes' => $notes, 'numdoc' => $numdoc, 'tva_rate' => $tva, 'store_id' => 1,
                    ]);
                    $souche->last_number += 1; $souche->save();
                } else {
                    SalesOrderLine::where('sales_order_id', $so->id)->delete();
                }

                $total = 0.0;
                foreach ($linked as $l) {
                    $item = Item::find($l->item_id);
                    if (!$item) continue;
                    $pu = (float) ($l->list_ht !== null ? $l->list_ht : $l->unit_ht); $rem = (float) $l->remise; $ht = round($pu * $l->qty * (1 - $rem / 100), 2); $total += $ht;
                    SalesOrderLine::create([
                        'sales_order_id' => $so->id, 'article_code' => $item->code, 'ordered_quantity' => $l->qty, 'unit_price_ht' => $pu,
                        'unit_price_ttc' => $pu * (1 - $rem / 100) * (1 + $tva / 100), 'remise' => $rem, 'total_ligne_ht' => $ht, 'total_ligne_ttc' => $ht * (1 + $tva / 100),
                        'supplier_id' => null, 'unit_coast' => $item->cost_price ?? 0, 'discount_coast' => 0,
                    ]);
                }
                $so->update(['total_ht' => $total, 'total_ttc' => $total * (1 + $tva / 100), 'notes' => $notes]);
                $q->update(['sales_order_id' => $so->id, 'erp_numdoc' => $so->numdoc]);
                return ['ok' => true, 'erp_numdoc' => $so->numdoc, 'erp_url' => route('sales.edit', $so->id), 'skipped' => $skipped->count()];
            });
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal quote to erp failed', ['id' => $id, 'e' => $e->getMessage()]);
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
        return response()->json($res, !empty($res['ok']) ? 200 : 422);
    }

    public function decline(Request $request, $id)
    {
        $v = $request->validate(['reason' => 'required|string|max:500']);
        $q = PortalQuote::findOrFail($id);
        if (!in_array($q->status, ['new', 'processing', 'negotiating', 'answered'], true)) return response()->json(['ok' => false, 'message' => 'Cette demande est clôturée.'], 409);
        $q->update(['status' => 'declined', 'vendor_message' => $v['reason'], 'answered_at' => now(), 'answered_by' => Auth::id(), 'decided_at' => now()]);
        $q->log('vendor', 'declined', $v['reason'], optional(Auth::user())->name);
        return response()->json(['ok' => true, 'number' => $q->number, 'mailed' => $this->mailClient($q)]);
    }

    public function note(Request $request, $id)
    {
        $v = $request->validate(['internal_note' => 'nullable|string|max:1000']);
        PortalQuote::findOrFail($id)->update(['internal_note' => $v['internal_note'] ?? null]);
        return response()->json(['ok' => true]);
    }

    /** Alertes ERP : demandes à traiter + nouveaux échanges CLIENT (demande, modification, acceptation) depuis l'événement `since`. */
    public function count(Request $request)
    {
        $lastId = (int) DB::table('portal_quote_events')->where('actor', 'client')->max('id');
        $since = (int) $request->query('since', -1);
        $labels = ['created' => 'NOUVELLE DEMANDE DE DEVIS', 'negotiate' => 'MODIFICATION DEMANDÉE', 'accepted' => 'DEVIS ACCEPTÉ'];
        $fresh = [];
        if ($since >= 0 && $lastId > $since) {
            $evs = DB::table('portal_quote_events')->where('actor', 'client')->where('id', '>', $since)->whereIn('type', array_keys($labels))->orderBy('id')->limit(5)->get();
            $quotes = PortalQuote::with('customer:id,name')->whereIn('id', $evs->pluck('quote_id')->all())->get()->keyBy('id');
            $done = [];
            foreach ($evs as $e) {
                $q = $quotes->get($e->quote_id); if (!$q) continue;
                if (isset($done[$q->id . ':' . $e->type])) continue; $done[$q->id . ':' . $e->type] = 1;   // un seul popup par devis et par type d'événement
                $fresh[] = ['ev' => (int) $e->id, 'id' => $q->id, 'label' => $labels[$e->type], 'number' => $q->number, 'customer' => optional($q->customer)->name, 'lines' => $q->lines_count, 'vehicle' => $q->vehicle_text];
            }
        }
        // « accepté » compte aussi : le vendeur doit préparer la commande
        return response()->json([
            'todo' => PortalQuote::whereIn('status', ['new', 'processing', 'negotiating', 'accepted'])->count(),
            'last_id' => $lastId, 'fresh' => $fresh, 'url' => route('b2b.quotes'),
        ]);
    }

    /** Prévient le client par e-mail (si son adresse est valide), après la réponse HTTP, sans jamais bloquer. */
    private function mailClient(PortalQuote $q)
    {
        try {
            $q->loadMissing('user');
            $to = $q->user ? trim((string) $q->user->email) : '';
            if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
            $s = B2bSetting::current();
            $body = 'Bonjour,' . "\n\n" . ($q->status === 'answered'
                ? 'Votre devis ' . $q->number . ' est disponible' . ($q->valid_until ? ' (valable jusqu\'au ' . $q->valid_until->format('d/m/Y') . ')' : '') . '. Connectez-vous au portail pour le consulter et l\'accepter.'
                : 'Votre demande ' . $q->number . ' a reçu une réponse. Connectez-vous au portail pour la consulter.') . "\n\n" . ($s->company_name ?: '');
            $subject = 'Votre devis ' . $q->number;
            dispatch(function () use ($to, $subject, $body) {
                try { \Illuminate\Support\Facades\Mail::raw($body, function ($m) use ($to, $subject) { $m->to($to)->subject($subject); }); }
                catch (\Throwable $e) { Log::channel('single')->warning('portal quote client mail failed: ' . $e->getMessage()); }
            })->afterResponse();
            return true;
        } catch (\Throwable $e) { return false; }
    }
}
