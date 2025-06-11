<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Carbon\Carbon;
use App\Models\User;
use XelentAbrar\HospitalInventory\Models\Inventory\Uom;
use XelentAbrar\HospitalInventory\Models\Inventory\Salt;
use XelentAbrar\HospitalInventory\Models\Inventory\Stock;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemType;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemCategory;
use Illuminate\Database\Eloquent\Model;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepassDetail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'product_code',
        'sku',
        'uom_id',
        'item_type_id',
        'item_salt_id',
        'item_category_id',
        'opening_stock',
        'short_description',
        'featured_image',
        'min_qty',
        'max_qty',
        'status','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
     public function getdateFieldAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Karachi')->format('d-m-Y');
    }
    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }
    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }
    public function item_category()
    {
        return $this->belongsTo(ItemCategory::class);
    }public function salt()
    {
        return $this->belongsTo(Salt::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function inwardGatepassDetails()
    {
        return $this->hasMany(InwardGatepassDetail::class, 'product_id');
    }

    public function materialIssueNoteDetails()
    {
        return $this->hasMany(MaterialIssueNoteDetail::class, 'product_id');
    }

    public function materialReturnNoteDetails()
    {
        return $this->hasMany(MaterialReturnNoteDetail::class, 'product_id');
    }
}
