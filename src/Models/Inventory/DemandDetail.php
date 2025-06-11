<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'demand_id',
        'product_id',
        'qty',
    ];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
