<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salt extends Model
{
    use HasFactory;
    protected $table = 'salt_items';
    protected $fillable = [
        'salt_name',
        'status',
    ];
}
