<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMS\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceiptNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_no',
        'inward_gatepass_id',
        'date',
        'remarks','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function inwardGatepass()
    {
        return $this->belongsTo(InwardGatepass::class);
    }

}
