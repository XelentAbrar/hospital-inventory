<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abrv',
        'type',
        'status',
    ];
}
