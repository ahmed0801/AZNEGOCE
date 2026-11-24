<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    protected $table = 'print_jobs';
    protected $guarded = ['id'];

    protected $casts = [
        'claimed_at' => 'datetime',
        'printed_at' => 'datetime',
    ];

    const MAX_ATTEMPTS = 3;

    /** Met un ticket en file d'attente pour l'imprimante réglée dans les paramètres B2B. */
    public static function enqueue($type, $title, $payloadBinary, $referenceId = null, $copies = null)
    {
        $s = B2bSetting::current();
        if (empty($s->printer_ip)) {
            throw new \RuntimeException("Aucune adresse IP d'imprimante n'est renseignée.");
        }
        return static::create([
            'type'         => $type,
            'title'        => $title,
            'reference_id' => $referenceId,
            'printer_ip'   => $s->printer_ip,
            'printer_port' => $s->printer_port ?: 9100,
            'copies'       => $copies ?: max(1, (int) $s->print_copies),
            'payload'      => base64_encode($payloadBinary),
            'status'       => 'pending',
        ]);
    }
}
