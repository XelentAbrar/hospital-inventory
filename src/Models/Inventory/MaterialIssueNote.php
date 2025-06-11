<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMS\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialIssueNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_no',
        'department_id',
        'date',
        'remarks','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
     public function getdateFieldAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Karachi')->format('d-m-Y');
    }
    public function detail()
    {
        return $this->hasMany(MaterialIssueNoteDetail::class);
    }

}
