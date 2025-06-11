<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'po_no',
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
     public function getdateFieldAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Karachi')->format('d-m-Y');
    }
    public function detail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

}
