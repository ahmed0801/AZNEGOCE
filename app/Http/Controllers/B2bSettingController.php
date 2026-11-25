<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\PrintJob;
use App\Services\EscPosBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class B2bSettingController extends Controller
{
    private function guard()
    {
        // même règle que « Paramètres Utilisateurs » : pas pour les vendeurs
        if (Auth::user() && Auth::user()->role === 'vendeur') abort(403);
    }

    public function edit()
    {
        $this->guard();
        return view('b2b_settings', [
            's'    => B2bSetting::current(),
            'jobs' => PrintJob::orderByDesc('id')->limit(6)->get(['id', 'type', 'title', 'status', 'error', 'created_at', 'printed_at']),
        ]);
    }

    public function update(Request $request)
    {
        $this->guard();

        $data = $request->validate([
            'company_name'        => 'nullable|string|max:150',
            'tagline'             => 'nullable|string|max:200',
            'primary_color'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'welcome_message'     => 'nullable|string|max:2000',
            'maintenance_message' => 'nullable|string|max:255',
            'phone'               => 'nullable|string|max:50',
            'whatsapp'            => 'nullable|string|max:50',
            'email'               => 'nullable|email|max:150',
            'website'             => 'nullable|string|max:150',
            'address'             => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:100',
            'opening_hours'       => 'nullable|string|max:255',
            'legal_footer'        => 'nullable|string|max:255',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'min_margin_percent'  => 'nullable|numeric|min:0|max:500',
            'stock_display'       => 'required|in:exact,threshold,hidden',
            'zero_price_mode'     => 'sometimes|in:allow,hide,block,both',
            'portal_mode'         => 'sometimes|in:full,quotes',
            'portal_show_erp_quotes' => 'sometimes|boolean',
            'stock_low_threshold' => 'nullable|integer|min:0|max:100000',
            'print_document'      => 'required|in:order,delivery_note',
            'printer_name'        => 'nullable|string|max:100',
            'printer_ip'          => 'nullable|ip',
            'printer_port'        => 'nullable|integer|min:1|max:65535',
            'paper_width'         => 'required|in:80mm,58mm,A4',
            'print_copies'        => 'nullable|integer|min:1|max:5',
            'notification_email'  => 'nullable|email|max:150',
            'logo'                => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        // Si l'impression automatique est active, l'imprimante doit être renseignée
        if ($request->boolean('auto_print') && empty($data['printer_ip'])) {
            return back()->withInput()->withErrors(['printer_ip' => "Renseignez l'adresse IP de l'imprimante pour activer l'impression automatique."]);
        }

        $s = B2bSetting::current();

        foreach (B2bSetting::BOOLEANS as $b) {
            $data[$b] = $request->boolean($b);
        }
        if (empty($data['portal_mode'])) $data['portal_mode'] = $s->portal_mode ?: 'full';
        if (empty($data['zero_price_mode'])) $data['zero_price_mode'] = $s->zero_price_mode ?: 'both';
        $data['min_margin_percent']  = isset($data['min_margin_percent']) ? $data['min_margin_percent'] : 0;
        $data['min_order_amount']    = isset($data['min_order_amount']) ? $data['min_order_amount'] : 0;
        $data['stock_low_threshold'] = isset($data['stock_low_threshold']) ? $data['stock_low_threshold'] : 5;
        $data['printer_port']        = !empty($data['printer_port']) ? $data['printer_port'] : 9100;
        $data['print_copies']        = !empty($data['print_copies']) ? $data['print_copies'] : 1;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $dir  = public_path('uploads/b2b');
            File::ensureDirectoryExists($dir);
            $name = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            if ($s->logo_path && strpos($s->logo_path, 'uploads/b2b/') === 0 && File::exists(public_path($s->logo_path))) {
                @File::delete(public_path($s->logo_path));
            }
            $data['logo_path'] = 'uploads/b2b/' . $name;
        }
        unset($data['logo']);

        if ($request->boolean('remove_logo') && !$request->hasFile('logo')) {
            if ($s->logo_path && strpos($s->logo_path, 'uploads/b2b/') === 0 && File::exists(public_path($s->logo_path))) {
                @File::delete(public_path($s->logo_path));
            }
            $data['logo_path'] = null;
        }

        $s->update($data);

        return redirect()->route('b2b.settings.edit')->with('success', 'Paramètres du portail B2B enregistrés.');
    }

    /** Met un ticket de test en file : l'agent du magasin l'imprimera dans quelques secondes. */
    public function testPrinter(Request $request)
    {
        $this->guard();
        $v = $request->validate(['printer_ip' => 'required|ip', 'printer_port' => 'nullable|integer|min:1|max:65535']);

        $s = B2bSetting::current();
        // le test utilise les valeurs saisies à l'écran, même non enregistrées
        $isPublic = filter_var($v['printer_ip'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        if ($isPublic) {
            return response()->json(['ok' => false, 'message' => 'Seules les adresses du réseau local (192.168.x.x, 10.x.x.x, 172.16-31.x.x) sont acceptées.']);
        }
        if (empty($s->agent_token)) {
            return response()->json(['ok' => false, 'message' => "Générez d'abord le jeton de l'agent (bouton ci-dessous), puis installez l'agent au magasin."]);
        }

        $job = PrintJob::create([
            'type'         => 'test',
            'title'        => 'Ticket de test',
            'printer_ip'   => $v['printer_ip'],
            'printer_port' => !empty($v['printer_port']) ? (int) $v['printer_port'] : 9100,
            'copies'       => 1,
            'payload'      => base64_encode(EscPosBuilder::testTicket($s->company_name, $s->paper_width)),
            'status'       => 'pending',
        ]);

        $online = $s->agent_last_seen && $s->agent_last_seen->gt(now()->subSeconds(60));
        return response()->json([
            'ok'      => true,
            'job_id'  => $job->id,
            'online'  => (bool) $online,
            'message' => $online ? 'Ticket de test envoyé à l\'agent…' : "Ticket mis en file. L'agent du magasin ne répond pas pour l'instant : il l'imprimera dès qu'il sera en ligne.",
        ]);
    }

    /** Statut d'un ticket + état de l'agent (interrogé par la page toutes les 2 s après un test). */
    public function printStatus(Request $request)
    {
        $this->guard();
        $s = B2bSetting::current();
        $out = [
            'agent_online'    => (bool) ($s->agent_last_seen && $s->agent_last_seen->gt(now()->subSeconds(60))),
            'agent_last_seen' => $s->agent_last_seen ? $s->agent_last_seen->format('d/m/Y H:i:s') : null,
        ];
        if ($request->filled('job')) {
            $j = PrintJob::find((int) $request->job);
            $out['job'] = $j ? ['status' => $j->status, 'error' => $j->error] : null;
        }
        return response()->json($out);
    }

    /** Génère (ou régénère) le jeton secret de l'agent. L'ancien agent cesse immédiatement de fonctionner. */
    public function regenerateToken()
    {
        $this->guard();
        B2bSetting::current()->update(['agent_token' => Str::random(48), 'agent_last_seen' => null]);
        return redirect()->route('b2b.settings.edit')->with('success', "Nouveau jeton généré. Copiez-le dans la configuration de l'agent du magasin.")->with('open_tab', 'tab-print');
    }

    /** E-mail de test (envoi immédiat) : affiche la vraie raison si l'envoi échoue. */
    public function testMail(Request $request)
    {
        $this->guard();
        $v = $request->validate(['email' => 'required|email|max:150']);
        $mailer = config('mail.default');
        if ($mailer === 'log' || $mailer === 'array') {
            return response()->json(['ok' => false, 'message' => "MAIL_MAILER est « $mailer » dans le fichier .env : les e-mails sont seulement écrits dans storage/logs, jamais envoyés. Utilisez « smtp »."]);
        }
        try {
            \Illuminate\Support\Facades\Mail::raw("Ceci est un e-mail de test du portail B2B.\nSi vous le lisez, les notifications de commande fonctionneront.", function ($m) use ($v) {
                $m->to($v['email'])->subject('Test des notifications - portail B2B');
            });
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'Échec : ' . $e->getMessage() . ' (expéditeur utilisé : ' . config('mail.from.address') . ')']);
        }
        return response()->json(['ok' => true, 'message' => 'E-mail envoyé à ' . $v['email'] . ' (expéditeur : ' . config('mail.from.address') . '). Vérifiez aussi les courriers indésirables.']);
    }
}
