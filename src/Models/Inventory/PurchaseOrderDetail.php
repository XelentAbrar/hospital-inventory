<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'demand_detail_id',
        'product_id',
        'qty',
        'price',
        'tax_id',
        'tax_rate',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function demandDetail()
    {
        return $this->belongsTo(DemandDetail::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

}
