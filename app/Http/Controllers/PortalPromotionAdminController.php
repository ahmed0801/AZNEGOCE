<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\DiscountGroup;
use App\Models\Item;
use App\Models\PortalPromotion;
use App\Models\PortalPromotionItem;
use App\Services\B2bPricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Promotions du portail B2B (ERP) : prix promo ou % sur des articles, avec dates de validité. */
class PortalPromotionAdminController extends Controller
{
    const STATES = ['running' => 'En cours', 'upcoming' => 'À venir', 'ended' => 'Terminée', 'off' => 'Désactivée'];

    public function index()
    {
        $s = B2bSetting::current();
        $groups = DiscountGroup::where('type', DiscountGroup::TYPE_CLIENT)->orderBy('name')->get(['id', 'name'])->all();
        return view('b2b_promotions', [
            'groups' => $groups,
            'minMargin' => $s->margin_guard ? (float) $s->min_margin_percent : null,
        ]);
    }

    private function scopeState($q, $state)
    {
        $now = now();
        if ($state === 'running')  return $q->where('active', 1)->where('starts_at', '<=', $now)->where('ends_at', '>=', $now);
        if ($state === 'upcoming') return $q->where('active', 1)->where('starts_at', '>', $now);
        if ($state === 'ended')    return $q->where('active', 1)->where('ends_at', '<', $now);
        return $q->where('active', 0);
    }

    public function data(Request $request)
    {
        $state = $request->query('status', 'running');
        if (!isset(self::STATES[$state])) $state = 'running';
        $counts = [];
        foreach (array_keys(self::STATES) as $k) { $counts[$k] = $this->scopeState(PortalPromotion::query(), $k)->count(); }
        $rows = $this->scopeState(PortalPromotion::query(), $state)->withCount('items')->orderBy($state === 'ended' ? 'ends_at' : 'starts_at', $state === 'ended' ? 'desc' : 'asc')->limit(200)->get();
        $gname = DB::table('portal_promotion_groups as g')->join('discount_groups as d', 'd.id', '=', 'g.discount_group_id')->select('g.promotion_id', 'd.name')->get()->groupBy('promotion_id');
        return response()->json([
            'counts' => $counts,
            'rows' => $rows->map(function ($p) use ($gname) {
                return [
                    'id' => $p->id, 'name' => $p->name, 'items' => $p->items_count, 'state' => $p->state, 'state_label' => self::STATES[$p->state],
                    'starts' => $p->starts_at->format('d/m/Y H:i'), 'ends' => $p->ends_at->format('d/m/Y H:i'),
                    'audience' => $p->audience === 'groups' ? ($gname->has($p->id) ? $gname[$p->id]->pluck('name')->implode(', ') : 'Aucun groupe') : 'Tous les clients',
                ];
            })->all(),
        ]);
    }

    private function costNet(Item $it)
    {
        return round((float) $it->cost_price * (1 - ((float) $it->remise_achat) / 100), 2);
    }

    private function itemRow(Item $it, $extra = [])
    {
        return $extra + [
            'item_id' => $it->id, 'code' => $it->code, 'name' => $it->name, 'brand' => optional($it->brand)->name,
            'sale_price' => round((float) $it->sale_price, 2), 'cost_net' => $this->costNet($it),
        ];
    }

    public function show($id)
    {
        $p = PortalPromotion::findOrFail($id);
        $items = PortalPromotionItem::with('item.brand')->where('promotion_id', $p->id)->orderBy('id')->get()->filter(function ($r) { return $r->item; })->map(function ($r) {
            return $this->itemRow($r->item, [
                'mode' => $r->mode, 'promo_price' => $r->promo_price !== null ? round((float) $r->promo_price, 2) : null,
                'percent' => $r->percent !== null ? (float) $r->percent : null, 'amount' => $r->amount !== null ? round((float) $r->amount, 2) : null, 'max_qty' => $r->max_qty, 'sold_qty' => (int) $r->sold_qty,
            ]);
        })->values()->all();
        return response()->json([
            'id' => $p->id, 'name' => $p->name, 'description' => $p->description, 'active' => (bool) $p->active,
            'starts_at' => $p->starts_at->format('Y-m-d\TH:i'), 'ends_at' => $p->ends_at->format('Y-m-d\TH:i'),
            'audience' => $p->audience, 'group_ids' => DB::table('portal_promotion_groups')->where('promotion_id', $p->id)->pluck('discount_group_id')->map(function ($v) { return (int) $v; })->all(),
            'items' => $items,
        ]);
    }

    public function save(Request $request, $id = null)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'active' => 'nullable|boolean',
            'audience' => 'required|in:all,groups',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'integer',
            'items' => 'required|array|min:1|max:500',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.mode' => 'required|in:price,percent,amount',
            'items.*.promo_price' => 'nullable|numeric|min:0|max:9999999',
            'items.*.percent' => 'nullable|numeric|min:0|max:100',
            'items.*.amount' => 'nullable|numeric|min:0|max:9999999',
            'items.*.max_qty' => 'nullable|integer|min:1|max:1000000',
        ], [
            'ends_at.after' => 'La date de fin doit être après la date de début.',
            'items.required' => 'Ajoutez au moins un article.', 'items.min' => 'Ajoutez au moins un article.',
        ]);
        if ($data['audience'] === 'groups' && empty($data['group_ids'])) {
            return response()->json(['message' => 'Choisissez au moins un groupe de clients, ou « Tous les clients ».'], 422);
        }
        $seen = [];
        foreach ($data['items'] as $i => $l) {
            if (isset($seen[$l['item_id']])) return response()->json(['message' => 'Un article est présent deux fois dans la liste.'], 422);
            $seen[$l['item_id']] = true;
            if ($l['mode'] === 'price' && !isset($l['promo_price'])) return response()->json(['message' => 'Ligne ' . ($i + 1) . ' : indiquez le prix promo.'], 422);
            if ($l['mode'] === 'amount' && !isset($l['amount'])) return response()->json(['message' => 'Ligne ' . ($i + 1) . ' : indiquez le montant de la remise.'], 422);
            if ($l['mode'] === 'percent' && !isset($l['percent'])) return response()->json(['message' => 'Ligne ' . ($i + 1) . ' : indiquez le pourcentage.'], 422);
        }

        $promo = DB::transaction(function () use ($data, $id) {
            $attrs = [
                'name' => trim($data['name']), 'description' => $data['description'] ?? null,
                'starts_at' => Carbon::parse($data['starts_at']), 'ends_at' => Carbon::parse($data['ends_at']),
                'active' => !empty($data['active']), 'audience' => $data['audience'],
            ];
            if ($id) { $p = PortalPromotion::lockForUpdate()->findOrFail($id); $p->update($attrs); }
            else { $p = PortalPromotion::create($attrs); }

            DB::table('portal_promotion_groups')->where('promotion_id', $p->id)->delete();
            if ($data['audience'] === 'groups') {
                foreach (array_unique($data['group_ids']) as $gid) { DB::table('portal_promotion_groups')->insert(['promotion_id' => $p->id, 'discount_group_id' => (int) $gid]); }
            }

            $old = PortalPromotionItem::where('promotion_id', $p->id)->get()->keyBy('item_id');   // on conserve les quantités déjà vendues
            $keep = [];
            foreach ($data['items'] as $l) {
                $vals = [
                    'mode' => $l['mode'],
                    'promo_price' => $l['mode'] === 'price' ? $l['promo_price'] : null,
                    'percent' => $l['mode'] === 'percent' ? $l['percent'] : null,
                    'amount' => $l['mode'] === 'amount' ? $l['amount'] : null,
                    'max_qty' => isset($l['max_qty']) && $l['max_qty'] !== '' ? (int) $l['max_qty'] : null,
                ];
                if ($old->has($l['item_id'])) { $old[$l['item_id']]->update($vals); }
                else { PortalPromotionItem::create($vals + ['promotion_id' => $p->id, 'item_id' => $l['item_id']]); }
                $keep[] = $l['item_id'];
            }
            PortalPromotionItem::where('promotion_id', $p->id)->whereNotIn('item_id', $keep)->delete();
            return $p;
        });
        B2bPricingService::forgetPromos();
        return response()->json(['ok' => true, 'id' => $promo->id, 'message' => 'Promotion « ' . $promo->name . ' » enregistrée.']);
    }

    // ───────────── Informer les clients par e-mail ─────────────
    // Action SÉPARÉE de l'enregistrement : un problème de serveur d'e-mail ne peut jamais empêcher de créer ou modifier une promotion.

    /** Clients ayant un accès portail actif avec une adresse valide, et qui peuvent profiter de la promotion (public choisi). */
    private function recipients(PortalPromotion $p)
    {
        $users = B2bUser::where('status', 'active')->whereNotNull('email')->orderBy('id')->get(['id', 'customer_id', 'name', 'email']);
        $cust = Customer::whereIn('id', $users->pluck('customer_id')->unique()->all());
        if ($p->audience === 'groups') {
            $gids = DB::table('portal_promotion_groups')->where('promotion_id', $p->id)->pluck('discount_group_id')->all();
            $cust->whereIn('discount_group_id', $gids ?: [0]);
        }
        $cust = $cust->orderBy('name')->get(['id', 'name'])->keyBy('id');
        $last = [];
        try { $last = DB::table('portal_promotion_mailings')->where('promotion_id', $p->id)->where('status', 'sent')->select('customer_id', DB::raw('max(created_at) as at'))->groupBy('customer_id')->pluck('at', 'customer_id')->all(); } catch (\Throwable $e) { }
        $out = [];
        foreach ($users->groupBy('customer_id') as $cid => $us) {
            if (!$cust->has($cid)) continue;
            $mails = $us->filter(function ($u) { return filter_var(trim($u->email), FILTER_VALIDATE_EMAIL); });
            if (!$mails->count()) continue;
            $out[] = ['customer_id' => (int) $cid, 'name' => $cust[$cid]->name, 'emails' => $mails->pluck('email')->unique()->values()->all(),
                      'informed' => isset($last[$cid]) ? Carbon::parse($last[$cid])->format('d/m/Y H:i') : null];
        }
        usort($out, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
        return $out;
    }

    public function recipientsData($id)
    {
        $p = PortalPromotion::findOrFail($id);
        $s = B2bSetting::current();
        $list = $this->recipients($p);
        return response()->json([
            'promotion' => $p->name, 'recipients' => $list,
            'subject' => 'Nouvelle promotion : ' . $p->name,
            'message' => "Nous avons le plaisir de vous annoncer notre promotion « " . $p->name . " », valable du " . $p->starts_at->format('d/m/Y') . " au " . $p->ends_at->format('d/m/Y') . ".\n\nVos prix promotionnels sont déjà visibles dans votre espace client.",
            'mail_ready' => (string) config('mail.default', config('mail.driver')) !== 'log' && (string) config('mail.default', config('mail.driver')) !== 'array',
        ]);
    }

    /** Envoi par petits lots depuis le navigateur. Ne renvoie jamais d'erreur 500 : le détail des échecs est renvoyé pour affichage. */
    public function notify(Request $request, $id)
    {
        try {
            @set_time_limit(120);
            $p = PortalPromotion::findOrFail($id);
            $data = $request->validate(['customer_ids' => 'required|array|min:1|max:15', 'customer_ids.*' => 'integer', 'subject' => 'required|string|max:150', 'message' => 'required|string|max:2000']);
            if (!$p->active || $p->ends_at->lt(now())) return response()->json(['ok' => false, 'sent' => 0, 'failed' => 0, 'stopped' => true, 'message' => 'Cette promotion est désactivée ou terminée : rien n\'a été envoyé.']);
            $eligible = collect($this->recipients($p))->keyBy('customer_id');
            $s = B2bSetting::current();
            $brand = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $s->primary_color) ? $s->primary_color : '#1E2D4A';
            $base = ['subject' => $data['subject'], 'message' => $data['message'], 'promo' => $p->name, 'from' => $p->starts_at->format('d/m/Y'), 'to' => $p->ends_at->format('d/m/Y'),
                     'company' => $s->company_name ?: config('app.name'), 'brand' => $brand, 'url' => route('portal.promotions')];
            $sent = 0; $failed = 0; $errors = []; $streak = 0; $stopped = false; $done = [];
            foreach (array_unique($data['customer_ids']) as $cid) {
                if (!$eligible->has($cid)) continue;                       // jamais un client hors public
                if ($stopped) break;
                $r = $eligible[$cid]; $okOne = false; $lastErr = '';
                foreach ($r['emails'] as $to) {
                    try {
                        Mail::send(['html' => 'emails.portal_promotion', 'text' => 'emails.portal_promotion_text'], $base + ['name' => $r['name']], function ($m) use ($to, $data) { $m->to($to)->subject($data['subject']); });
                        $okOne = true;
                        $this->logMail($p->id, $cid, $to, 'sent', null);
                    } catch (\Throwable $e) {
                        $lastErr = mb_substr($e->getMessage(), 0, 200);
                        $this->logMail($p->id, $cid, $to, 'failed', $lastErr);
                        Log::channel('single')->warning('promo mail failed (' . $to . '): ' . $lastErr);
                    }
                }
                if ($okOne) { $sent++; $streak = 0; $done[] = (int) $cid; }
                else { $failed++; $streak++; $errors[] = ['customer' => $r['name'], 'error' => $lastErr]; if ($streak >= 2) $stopped = true; }   // serveur d'e-mail hors service : on arrête vite
            }
            return response()->json(['ok' => true, 'sent' => $sent, 'failed' => $failed, 'stopped' => $stopped, 'done' => $done, 'errors' => $errors,
                'message' => $stopped ? 'Le serveur d\'e-mail ne répond pas : envoi interrompu. La promotion est bien enregistrée, vous pourrez réessayer plus tard.' : null]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['ok' => false, 'sent' => 0, 'failed' => 0, 'stopped' => true, 'message' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Throwable $e) {
            Log::channel('single')->warning('promo notify error: ' . $e->getMessage());
            return response()->json(['ok' => false, 'sent' => 0, 'failed' => 0, 'stopped' => true, 'message' => 'Envoi impossible pour le moment. La promotion est bien enregistrée.']);
        }
    }

    private function logMail($promoId, $customerId, $email, $status, $error)
    {
        try { DB::table('portal_promotion_mailings')->insert(['promotion_id' => $promoId, 'customer_id' => $customerId, 'email' => $email, 'status' => $status, 'error' => $error, 'created_at' => now()]); } catch (\Throwable $e) { }
    }

    public function toggle($id)
    {
        $p = PortalPromotion::findOrFail($id);
        $p->update(['active' => !$p->active]);
        B2bPricingService::forgetPromos();
        return response()->json(['ok' => true, 'active' => (bool) $p->active, 'message' => $p->active ? 'Promotion activée.' : 'Promotion désactivée.']);
    }

    public function destroy($id)
    {
        $p = PortalPromotion::findOrFail($id);
        DB::transaction(function () use ($p) {
            DB::table('portal_promotion_groups')->where('promotion_id', $p->id)->delete();
            PortalPromotionItem::where('promotion_id', $p->id)->delete();
            $p->delete();
        });
        B2bPricingService::forgetPromos();
        return response()->json(['ok' => true, 'message' => 'Promotion supprimée.']);
    }

    private function norm($s)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $s));
    }

    public function items(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        if (mb_strlen($term) < 2) return response()->json(['rows' => []]);
        $n = $this->norm($term);
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
        $rows = Item::with('brand')->where(function ($w) use ($term, $n, $like) {
            $w->where('code', $term)->orWhere('code', $n)->orWhere('barcode', $term)->orWhere('Ref_TecDoc', $term)->orWhere('codefournisseur', $term)
              ->orWhere('code', 'like', $like . '%')->orWhere('name', 'like', '%' . $like . '%');
        })->orderByRaw('CASE WHEN code = ? THEN 0 WHEN code LIKE ? THEN 1 ELSE 2 END', [$term, $like . '%'])->limit(12)->get();
        return response()->json(['rows' => $rows->map(function ($it) { return $this->itemRow($it); })->all()]);
    }

    /** @return array [Item|null, motif d'échec] */
    private function matchItem($ref)
    {
        $n = $this->norm($ref);
        if (strlen($n) < 3) return [null, 'référence trop courte'];
        $vals = array_values(array_unique([trim($ref), $n]));
        $rows = Item::with('brand')->where(function ($w) use ($vals) {
            $w->whereIn('code', $vals)->orWhereIn('barcode', $vals)->orWhereIn('Ref_TecDoc', $vals)->orWhereIn('codefournisseur', $vals);
        })->limit(2)->get();
        if ($rows->count() > 1) return [null, 'plusieurs articles'];
        if ($rows->count() === 0) return [null, 'introuvable'];
        return [$rows->first(), ''];
    }

    private function num($v)
    {
        $v = str_replace([' ', "\xC2\xA0"], '', (string) $v);
        $v = str_replace(',', '.', preg_replace('/[^0-9,.\-]/', '', $v));
        return ($v !== '' && is_numeric($v)) ? (float) $v : null;
    }

    private function head($v)
    {
        $v = strtolower(trim((string) $v));
        $v = strtr($v, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'à' => 'a', 'ç' => 'c', 'ô' => 'o', 'û' => 'u', 'ï' => 'i']);
        return preg_replace('/[^a-z%€]/u', '', $v);
    }

    private function readSheet($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (in_array($ext, ['csv', 'txt'], true)) {
            $raw = file_get_contents($file->getRealPath());
            if (substr($raw, 0, 3) === "\xEF\xBB\xBF") $raw = substr($raw, 3);
            if (!mb_check_encoding($raw, 'UTF-8')) $raw = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252');
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            $sep = substr_count($lines[0] ?? '', ';') >= substr_count($lines[0] ?? '', ',') ? ';' : ',';
            $out = [];
            foreach ($lines as $l) { if (trim($l) !== '') $out[] = str_getcsv($l, $sep); }
            return $out;
        }
        $sheets = \Maatwebsite\Excel\Facades\Excel::toArray(new \stdClass(), $file);
        return isset($sheets[0]) ? $sheets[0] : [];
    }

    /** Import Excel/CSV avec aperçu : rien n'est enregistré, le navigateur ajoute ensuite les lignes valides à la promotion. */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|max:4096|mimes:xlsx,xls,csv,txt'], ['file.required' => 'Choisissez un fichier.', 'file.mimes' => 'Format accepté : Excel (.xlsx, .xls) ou CSV.', 'file.max' => 'Fichier trop volumineux (4 Mo maximum).']);
        try { $sheet = $this->readSheet($request->file('file')); }
        catch (\Throwable $e) { return response()->json(['message' => 'Fichier illisible : ' . $e->getMessage()], 422); }
        if (!count($sheet)) return response()->json(['message' => 'Le fichier est vide.'], 422);

        $alias = [
            'ref' => ['reference', 'ref', 'code', 'article', 'codearticle', 'refarticle'],
            'price' => ['prix', 'prixpromo', 'prixht', 'prixpromoht', 'pu', 'puht', 'prixpromotion'],
            'percent' => ['remise', 'remise%', '%', 'pourcentage', 'percent', 'promo%', 'tauxremise'],
            'amount' => ['remise€', 'remise€ht', 'remiseeuro', 'remiseeuros', 'remiseeur', 'remisemontant', 'montantremise', 'remisehtmontant', 'remiseenmontant'],
            'qty' => ['qtemax', 'quantitemax', 'qte', 'quantite', 'max', 'qtemaximale', 'plafond'],
        ];
        $map = []; $first = array_map(array($this, 'head'), $sheet[0]);
        foreach ($first as $i => $h) { foreach ($alias as $k => $names) { if ($h !== '' && in_array($h, $names, true) && !isset($map[$k])) $map[$k] = $i; } }
        $hasHeader = isset($map['ref']);
        if (!$hasHeader) { $map = ['ref' => 0, 'price' => 1]; }
        $rows = array_slice($sheet, $hasHeader ? 1 : 0, 500);

        $out = []; $seen = []; $ok = 0; $bad = 0;
        foreach ($rows as $idx => $r) {
            $line = $idx + ($hasHeader ? 2 : 1);
            $ref = trim((string) (isset($r[$map['ref']]) ? $r[$map['ref']] : ''));
            if ($ref === '') continue;
            $price = isset($map['price']) && isset($r[$map['price']]) ? $this->num($r[$map['price']]) : null;
            $pct = isset($map['percent']) && isset($r[$map['percent']]) ? $this->num($r[$map['percent']]) : null;
            $amt = isset($map['amount']) && isset($r[$map['amount']]) ? $this->num($r[$map['amount']]) : null;
            $qty = isset($map['qty']) && isset($r[$map['qty']]) ? $this->num($r[$map['qty']]) : null;
            $row = ['line' => $line, 'ref' => $ref, 'status' => 'ok', 'message' => ''];
            list($it, $why) = $this->matchItem($ref);
            if (!$it) { $row['status'] = 'error'; $row['message'] = ucfirst($why); }
            elseif (isset($seen[$it->id])) { $row['status'] = 'error'; $row['message'] = 'Article en double dans le fichier'; }
            elseif ($price === null && $pct === null && $amt === null) { $row['status'] = 'error'; $row['message'] = 'Prix promo, remise % ou remise € manquant'; }
            elseif ($price === null && $pct !== null && ($pct < 0 || $pct > 100)) { $row['status'] = 'error'; $row['message'] = 'Remise hors de 0 à 100 %'; }
            elseif ($price !== null && $price < 0) { $row['status'] = 'error'; $row['message'] = 'Prix négatif'; }
            elseif ($price === null && $pct === null && $amt !== null && $amt < 0) { $row['status'] = 'error'; $row['message'] = 'Remise négative'; }
            elseif ($price === null && $pct === null && $amt !== null && $amt >= (float) $it->sale_price) { $row['status'] = 'error'; $row['message'] = 'Remise en € supérieure ou égale au tarif'; }
            if ($it) $seen[$it->id] = true;
            if ($row['status'] === 'ok') {
                $mode = $price !== null ? 'price' : ($pct !== null ? 'percent' : 'amount');
                if (($price !== null && ($pct !== null || $amt !== null)) || ($price === null && $pct !== null && $amt !== null)) $row['message'] = 'Plusieurs colonnes renseignées : priorité au prix, puis au %, puis au montant';
                $eff = $mode === 'price' ? $price : ($mode === 'percent' ? round((float) $it->sale_price * (1 - $pct / 100), 2) : round((float) $it->sale_price - $amt, 2));
                if ($eff >= (float) $it->sale_price) { $row['status'] = 'warn'; $row['message'] = 'Prix promo ≥ tarif (' . number_format((float) $it->sale_price, 2, ',', ' ') . ') : la ligne sera sans effet'; }
                $row['item'] = $this->itemRow($it, [
                    'mode' => $mode, 'promo_price' => $mode === 'price' ? round($price, 2) : null, 'percent' => $mode === 'percent' ? $pct : null, 'amount' => $mode === 'amount' ? round($amt, 2) : null,
                    'max_qty' => ($qty !== null && $qty >= 1) ? (int) $qty : null, 'sold_qty' => 0,
                ]);
                $row['price'] = $eff;
            }
            $row['status'] === 'error' ? $bad++ : $ok++;
            $out[] = $row;
        }
        return response()->json(['rows' => $out, 'ok' => $ok, 'bad' => $bad, 'header' => $hasHeader]);
    }

    public function template()
    {
        $file = public_path('assets/templates/modele_import_promotion.xlsx');
        if (is_file($file)) return response()->download($file, 'modele_import_promotion.xlsx');
        $csv = "\xEF\xBB\xBF" . "reference;prix promo HT;remise %;remise € HT;qte max\r\nBP1234;12,50;;;100\r\nDF5678;;15;;\r\nXY9012;;;5;\r\n";
        return response($csv, 200, ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="modele_import_promotion.csv"']);
    }

    /** Liste collée : une ligne par article, « référence » ou « référence prix » (séparés par espace, point-virgule ou tabulation). */
    public function resolve(Request $request)
    {
        $text = (string) $request->input('text', '');
        $found = []; $missing = []; $dup = []; $seen = [];
        foreach (array_slice(preg_split('/\r\n|\r|\n/', $text), 0, 500) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = preg_split('/[;\t ]+/', $line);
            $ref = $parts[0];
            $price = null;
            if (isset($parts[1])) { $v = str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $parts[count($parts) - 1])); if ($v !== '' && is_numeric($v)) $price = round((float) $v, 2); }
            list($it, $why) = $this->matchItem($ref);
            if (!$it) { $missing[] = $ref . ($why ? ' (' . $why . ')' : ''); continue; }
            if (isset($seen[$it->id])) { $dup[] = $ref; continue; }
            $seen[$it->id] = true;
            $found[] = $this->itemRow($it, ['price_hint' => $price]);
        }
        return response()->json(['found' => $found, 'missing' => $missing, 'dup' => $dup]);
    }
}
