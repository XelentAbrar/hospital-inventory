<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardGatepassDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_gatepass_id',
        'purchase_order_detail_id',
        'product_id',
        'qty',
        'price',
        'tax_id',
        'tax_rate',
        'expiry_date',
    ];

    public function inwardGatepass()
    {
        return $this->belongsTo(InwardGatepass::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrderDetail()
    {
        return $this->belongsTo(PurchaseOrderDetail::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

}
