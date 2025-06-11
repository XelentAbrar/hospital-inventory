<?php

namespace XelentAbrar\HospitalInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIssueNoteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_issue_note_id',
        'product_id',
        'qty',
    ];

    public function materialIssueNote()
    {
        return $this->belongsTo(MaterialIssueNote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
