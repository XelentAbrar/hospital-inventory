<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InwardGatepass extends Model
{
    use HasFactory;
    protected $table = "inward_gatepass";
    protected $fillable = [
        'supplier_id',
        'gatepass_no',
        'date',
        'remarks','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detail()
    {
        return $this->hasMany(InwardGatepassDetail::class);
    }

}
