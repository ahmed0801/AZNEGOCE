<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldaImportSetting extends Model
{
    protected $table = 'golda_import_settings';

    protected $fillable = [
        'code_marque', 'nom_marque', 'brand_id', 'active',
        'update_cost_price', 'update_sale_price', 'update_description',
        'update_barcode', 'update_dimensions', 'create_new_items',
        'deactivate_removed', 'margin_override', 'supplier_code',
        'last_import_count', 'last_deactivated_count', 'last_imported_at','prefixe_tarif',
    ];

    protected $casts = [
        'active'              => 'boolean',
        'update_cost_price'   => 'boolean',
        'update_sale_price'   => 'boolean',
        'update_description'  => 'boolean',
        'update_barcode'      => 'boolean',
        'update_dimensions'   => 'boolean',
        'create_new_items'    => 'boolean',
        'deactivate_removed'  => 'boolean',
        'last_imported_at'    => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }
}