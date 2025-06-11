<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    use HasFactory;
    protected $table = 'supplier_contacts';
    protected $fillable = ['name', 'designation', 'department', 'contact', 'notes'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
