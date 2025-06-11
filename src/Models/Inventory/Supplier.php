<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'landline',
        'address',
        'postal_code',
        'city_id',
        'state_id',
        'country_id',
        'logo',
        'opening_balance',
        'opening_type',
        'tax_number',
        'notes',
        'status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function supplier_contact()
    {
        return $this->hasMany(SupplierContact::class);
    }

}
