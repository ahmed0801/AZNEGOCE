<?php

namespace App\Services;

use App\Models\PortalQuote;
use App\Models\PortalQuoteFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pièces jointes d'un devis (photos, carte grise, PDF…). Stockées HORS du dossier public (storage/app/portal_quotes/{devis}/),
 * servies uniquement par un contrôleur qui vérifie le client (portail) ou la session ERP (admin).
 */
class PortalQuoteFiles
{
    const MAX_FILES = 8;            // par devis et par émetteur
    const MAX_KB = 8192;
    const MIMES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];

    /** Règles de validation Laravel pour un champ « files.* ». */
    public static function rules()
    {
        return ['files' => 'nullable|array|max:' . self::MAX_FILES, 'files.*' => 'file|max:' . self::MAX_KB . '|mimetypes:' . implode(',', array_keys(self::MIMES))];
    }

    public static function messages()
    {
        return ['files.max' => 'Maximum ' . self::MAX_FILES . ' fichiers à la fois.', 'files.*.max' => 'Un fichier dépasse ' . (self::MAX_KB / 1024) . ' Mo.', 'files.*.mimetypes' => 'Formats acceptés : photos (JPG, PNG, WebP) et PDF.', 'files.*.file' => 'Fichier invalide.', 'files.*.uploaded' => 'Fichier trop volumineux pour le serveur. Réduisez sa taille (photo : envoyez-la en qualité moyenne).'];
    }

    /** Enregistre les fichiers ; renvoie le nombre ajouté. @param UploadedFile[]|null $files */
    public static function store(PortalQuote $q, $files, $by, $who)
    {
        $files = is_array($files) ? $files : [];
        $have = PortalQuoteFile::where('quote_id', $q->id)->where('by', $by)->count();
        $added = 0;
        foreach ($files as $f) {
            if (!$f || !$f->isValid() || $have + $added >= self::MAX_FILES * 2) continue;
            $mime = (string) $f->getMimeType();
            if (!isset(self::MIMES[$mime])) continue;
            $base = Str::slug(pathinfo((string) $f->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'fichier';
            $stored = $f->storeAs('portal_quotes/' . $q->id, Str::random(24) . '.' . self::MIMES[$mime], 'local');   // nom aléatoire : jamais celui de l'utilisateur
            PortalQuoteFile::create([
                'quote_id' => $q->id, 'by' => $by, 'who' => $who, 'name' => mb_substr($base, 0, 80) . '.' . self::MIMES[$mime],
                'path' => $stored, 'mime' => $mime, 'size' => (int) $f->getSize(), 'created_at' => now(),
            ]);
            $added++;
        }
        return $added;
    }

    /** Réponse de téléchargement/affichage ; le fichier doit appartenir à ce devis. */
    public static function response(PortalQuote $q, $fileId, $download = false)
    {
        $f = PortalQuoteFile::where('quote_id', $q->id)->where('id', $fileId)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($f->path), 404);
        $headers = ['Content-Type' => $f->mime, 'X-Content-Type-Options' => 'nosniff', 'Content-Security-Policy' => "default-src 'none'; img-src 'self' data:; style-src 'unsafe-inline'", 'Cache-Control' => 'private, max-age=600'];
        $path = Storage::disk('local')->path($f->path);
        return $download ? response()->download($path, $f->name, $headers) : response()->file($path, $headers + ['Content-Disposition' => 'inline; filename="' . $f->name . '"']);
    }

    /** Liste pour les vues. */
    public static function listFor(PortalQuote $q)
    {
        return PortalQuoteFile::where('quote_id', $q->id)->orderBy('id')->get();
    }
}
