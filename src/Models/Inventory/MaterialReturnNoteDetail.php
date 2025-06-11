<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReturnNoteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_return_note_id',
        'product_id',
        'qty',
    ];

    public function materialReturnNote()
    {
        return $this->belongsTo(MaterialReturnNote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
