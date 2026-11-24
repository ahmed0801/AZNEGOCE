<?php

namespace App\Services;

/**
 * Générateur minimal de tickets ESC/POS (imprimantes thermiques Epson et compatibles).
 * Les accents sont convertis en page de code CP858 (français).
 */
class EscPosBuilder
{
    private $buf = '';
    private $cols;

    public function __construct($paperWidth = '80mm')
    {
        $this->cols = $paperWidth === '58mm' ? 32 : 48;
        $this->buf .= "\x1B\x40";          // initialiser
        $this->buf .= "\x1B\x74\x13";      // page de code CP858
    }

    public function cols()
    {
        return $this->cols;
    }

    private function enc($text)
    {
        $text = str_replace(["’", "‘", "“", "”", "–", "—", "…", "€"], ["'", "'", '"', '"', '-', '-', '...', 'EUR'], (string) $text);
        $out = @iconv('UTF-8', 'CP858//TRANSLIT//IGNORE', $text);
        return $out === false ? preg_replace('/[^\x20-\x7E]/', '?', $text) : $out;
    }

    public function align($a)   // left | center | right
    {
        $n = ['left' => 0, 'center' => 1, 'right' => 2][$a] ?? 0;
        $this->buf .= "\x1B\x61" . chr($n);
        return $this;
    }

    public function bold($on = true)
    {
        $this->buf .= "\x1B\x45" . chr($on ? 1 : 0);
        return $this;
    }

    public function big($on = true)    // double hauteur + largeur
    {
        $this->buf .= "\x1D\x21" . chr($on ? 0x11 : 0x00);
        return $this;
    }

    public function text($t)
    {
        $this->buf .= $this->enc($t);
        return $this;
    }

    public function line($t = '')
    {
        $this->buf .= $this->enc($t) . "\n";
        return $this;
    }

    public function rule($char = '-')
    {
        return $this->line(str_repeat($char, $this->cols));
    }

    /** Ligne « libellé ........ valeur » alignée sur la largeur du papier. */
    public function pair($left, $right)
    {
        $left  = (string) $left;
        $right = (string) $right;
        $space = $this->cols - mb_strlen($right) - 1;
        if ($space < 1) $space = 1;
        if (mb_strlen($left) > $space) $left = mb_substr($left, 0, $space);
        return $this->line(str_pad($left, $space + (strlen($left) - mb_strlen($left)), ' ') . ' ' . $right);
    }

    public function feed($n = 3)
    {
        $this->buf .= str_repeat("\n", $n);
        return $this;
    }

    public function cut()
    {
        $this->buf .= "\x1D\x56\x42\x00";  // coupe partielle après avance
        return $this;
    }

    public function beep()
    {
        $this->buf .= "\x1B\x42\x02\x02";
        return $this;
    }

    public function build()
    {
        return $this->buf;
    }

    /** Ticket de test. */
    public static function testTicket($company, $paperWidth = '80mm')
    {
        $b = new self($paperWidth);
        $b->align('center')->bold()->big()->line($company ?: 'AZNEGOCE')->big(false)->bold(false)
          ->line('Portail B2B')->rule('=')
          ->line('TICKET DE TEST')
          ->line('Impression : OK')
          ->line('Accents : é è ê à ç ù ô î')
          ->line(date('d/m/Y H:i'))
          ->rule('=')->feed(4)->cut();
        return $b->build();
    }

    /** Ticket de commande portail (atelier / comptoir). */
    public static function orderTicket($company, $paperWidth, array $o)
    {
        $b = new self($paperWidth);
        $w = $b->cols();
        $m = function ($n) { return number_format((float) $n, 2, ',', ' '); };
        $b->align('center')->bold()->big()->line($company ?: 'AZNEGOCE')->big(false)->bold(false)
          ->line('COMMANDE PORTAIL B2B')->rule('=')
          ->align('left')->bold()->line($o['number'])->bold(false)
          ->line(date('d/m/Y H:i'))
          ->line('Client : ' . mb_strimwidth($o['customer'], 0, $w - 9, '', 'UTF-8'))
          ->line($o['mode'] === 'pickup' ? 'Retrait au magasin' : 'Livraison');
        if (!empty($o['po_ref'])) $b->line('Votre ref. : ' . mb_strimwidth($o['po_ref'], 0, $w - 13, '', 'UTF-8'));
        $b->rule('-');
        foreach ($o['lines'] as $l) {
            $b->bold()->line(mb_strimwidth($l['code'], 0, $w, '', 'UTF-8'))->bold(false);
            $b->line(mb_strimwidth($l['name'], 0, $w, '', 'UTF-8'));
            $b->pair($l['qty'] . ' x ' . $m($l['unit_ht']), $m($l['line_ht']));
        }
        $b->rule('-')->pair('Total HT', $m($o['total_ht']))->pair('TVA', $m($o['total_tva']))
          ->bold()->pair('Total TTC', $m($o['total_ttc']))->bold(false);
        if (!empty($o['note'])) { $b->rule('-')->line('Message :')->line(mb_strimwidth($o['note'], 0, $w * 3, '', 'UTF-8')); }
        $b->rule('=')->feed(4)->cut();
        return $b->build();
    }
}
