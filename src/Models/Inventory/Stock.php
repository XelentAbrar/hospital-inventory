<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'total_qty',
        'opening_stock',
        'used_stock',
        'min_qty','current_stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}





