<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Achats — étape 2 : le stock entre à la RÉCEPTION (plus à la validation de la commande).
 *
 * Migration 100 % additive : aucune colonne n'est supprimée, aucune donnée n'est effacée.
 * Elle peut être rejouée sans risque (chaque ajout est protégé par hasColumn).
 *
 * BASCULE (important) : jusqu'ici, valider une commande d'achat ajoutait déjà le stock.
 * Toutes les commandes et réceptions EXISTANTES sont donc marquées « stock déjà appliqué »
 * pour qu'elles ne soient jamais comptées une seconde fois.
 */
class PreparePurchaseFlow extends Migration
{
    public function up()
    {
        /* ───────────── RÉCEPTIONS : un vrai document ───────────── */
        Schema::table('receptions', function (Blueprint $t) {
            if (!Schema::hasColumn('receptions', 'numdoc'))       $t->string('numdoc')->nullable()->after('id');
            if (!Schema::hasColumn('receptions', 'supplier_id'))  $t->unsignedBigInteger('supplier_id')->nullable()->index()->after('numdoc');
            if (!Schema::hasColumn('receptions', 'store_id'))     $t->unsignedBigInteger('store_id')->nullable()->after('supplier_id');
            if (!Schema::hasColumn('receptions', 'supplier_ref')) $t->string('supplier_ref')->nullable();           // N° BL du fournisseur
            if (!Schema::hasColumn('receptions', 'invoiced'))     $t->boolean('invoiced')->default(false);
            if (!Schema::hasColumn('receptions', 'stock_applied'))$t->boolean('stock_applied')->default(false);      // le stock a-t-il été ajouté ?
            if (!Schema::hasColumn('receptions', 'notes'))        $t->text('notes')->nullable();
        });

        // Index unique sur le numéro (en deux temps : compatible MySQL et SQLite ; ignoré s'il existe déjà)
        try {
            Schema::table('receptions', function (Blueprint $t) { $t->unique('numdoc', 'receptions_numdoc_unique'); });
        } catch (\Throwable $e) { /* déjà présent */ }

        // Une réception peut exister sans commande (réception directe)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE receptions MODIFY purchase_order_id BIGINT UNSIGNED NULL');
        }

        Schema::table('reception_lines', function (Blueprint $t) {
            if (!Schema::hasColumn('reception_lines', 'purchase_order_line_id')) $t->unsignedBigInteger('purchase_order_line_id')->nullable()->index()->after('reception_id');
            if (!Schema::hasColumn('reception_lines', 'unit_price_ht'))  $t->decimal('unit_price_ht', 15, 2)->default(0);
            if (!Schema::hasColumn('reception_lines', 'remise'))         $t->decimal('remise', 5, 2)->default(0);
            if (!Schema::hasColumn('reception_lines', 'tva'))            $t->decimal('tva', 5, 2)->default(0);
            if (!Schema::hasColumn('reception_lines', 'total_ligne_ht')) $t->decimal('total_ligne_ht', 15, 2)->default(0);
        });

        /* ───────────── COMMANDES ───────────── */
        Schema::table('purchase_orders', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_orders', 'purchase_project_id')) $t->unsignedBigInteger('purchase_project_id')->nullable()->index();
            if (!Schema::hasColumn('purchase_orders', 'store_id'))            $t->unsignedBigInteger('store_id')->nullable();
            if (!Schema::hasColumn('purchase_orders', 'reception_status'))    $t->string('reception_status', 20)->default('a_recevoir'); // a_recevoir | partielle | recue
        });

        Schema::table('purchase_order_lines', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_order_lines', 'tva'))               $t->decimal('tva', 5, 2)->default(0);
            if (!Schema::hasColumn('purchase_order_lines', 'received_quantity')) $t->decimal('received_quantity', 12, 2)->default(0);
        });

        /* ───────────── STOCK / FACTURES ───────────── */
        Schema::table('stock_movements', function (Blueprint $t) {
            if (!Schema::hasColumn('stock_movements', 'reception_line_id')) $t->unsignedBigInteger('reception_line_id')->nullable()->index();
        });

        Schema::table('purchase_invoices', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_invoices', 'due_date'))     $t->date('due_date')->nullable();
            if (!Schema::hasColumn('purchase_invoices', 'supplier_ref')) $t->string('supplier_ref')->nullable();    // N° facture du fournisseur
        });

        Schema::table('purchase_invoice_lines', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_invoice_lines', 'reception_id')) $t->unsignedBigInteger('reception_id')->nullable()->index();
        });

        /* ───────────── SOUCHE des réceptions ───────────── */
        if (Schema::hasTable('souches') && !DB::table('souches')->where('type', 'reception_achat')->exists()) {
            DB::table('souches')->insert([
                'code'          => DB::table('souches')->where('code', 'RECEPTION_ACHAT')->exists() ? 'RECEPTION_ACHAT_2' : 'RECEPTION_ACHAT',
                'name'          => 'Réception achat',
                'prefix'        => 'REC',
                'last_number'   => 0,
                'number_length' => 6,
                'suffix'        => null,
                'type'          => 'reception_achat',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        /* ───────────── BASCULE DES DONNÉES EXISTANTES ───────────── */
        $this->backfill();
    }

    private function backfill(): void
    {
        // 1) Commandes : TVA de ligne = taux figé de la commande ; non-brouillon = stock déjà entré => « reçue »
        DB::table('purchase_orders')->orderBy('id')->chunkById(300, function ($orders) {
            foreach ($orders as $o) {
                $applied = in_array($o->status, ['validée', 'clôturée', 'reçu'], true);

                DB::table('purchase_order_lines')->where('purchase_order_id', $o->id)->update(['tva' => $o->tva_rate ?? 0]);

                if ($applied) {
                    DB::table('purchase_order_lines')->where('purchase_order_id', $o->id)
                        ->update(['received_quantity' => DB::raw('ordered_quantity')]);
                    DB::table('purchase_orders')->where('id', $o->id)->update(['reception_status' => 'recue']);
                }
            }
        });

        // 2) Réceptions existantes : numéro historique, fournisseur de la commande, stock déjà appliqué
        $suppliers = DB::table('purchase_orders')->pluck('supplier_id', 'id');
        DB::table('receptions')->orderBy('id')->chunkById(300, function ($recs) use ($suppliers) {
            foreach ($recs as $r) {
                $upd = ['stock_applied' => 1];
                if (empty($r->numdoc))      $upd['numdoc'] = 'REC-H' . str_pad($r->id, 6, '0', STR_PAD_LEFT);
                if (empty($r->supplier_id)) $upd['supplier_id'] = $suppliers[$r->purchase_order_id] ?? null;
                DB::table('receptions')->where('id', $r->id)->update($upd);
            }
        });
    }

    public function down()
    {
        $drop = [
            'receptions'            => ['numdoc', 'supplier_id', 'store_id', 'supplier_ref', 'invoiced', 'stock_applied', 'notes'],
            'reception_lines'       => ['purchase_order_line_id', 'unit_price_ht', 'remise', 'tva', 'total_ligne_ht'],
            'purchase_orders'       => ['purchase_project_id', 'store_id', 'reception_status'],
            'purchase_order_lines'  => ['tva', 'received_quantity'],
            'stock_movements'       => ['reception_line_id'],
            'purchase_invoices'     => ['due_date', 'supplier_ref'],
            'purchase_invoice_lines'=> ['reception_id'],
        ];
        foreach ($drop as $table => $cols) {
            foreach ($cols as $c) {
                if (Schema::hasColumn($table, $c)) {
                    Schema::table($table, function (Blueprint $t) use ($c) {
                        if ($c === 'numdoc') { $t->dropUnique('receptions_numdoc_unique'); }
                        $t->dropColumn($c);
                    });
                }
            }
        }
        DB::table('souches')->where('type', 'reception_achat')->delete();
    }
}
