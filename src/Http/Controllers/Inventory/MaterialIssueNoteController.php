<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Traits\Stock;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use App\Models\HRMS\Department;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNote;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNoteDetail;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\MaterialIssueNoteRequest;

class MaterialIssueNoteController extends Controller
{
    use Stock;

    public function index()
    {
        $materialIssueNotes = MaterialIssueNote::with('department:id,name')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/MaterialIssueNote/Index', [
            'materialIssueNotes' => $materialIssueNotes,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $products = Product::with('stock')->whereStatus('active')->select('id', 'name')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->whereType('inventory')->orderBy('name')->get();
        $min_no = MaterialIssueNote::orderBy('id','desc')->limit(1)->value('min_no') > 0 ? MaterialIssueNote::orderBy('id','desc')->limit(1)->value('min_no') + 1 : date('Y').'1';

        return Inertia::render('Inventory/MaterialIssueNote/Create', [
            'csrf_token' => csrf_token(),
            'products' => $products,
            'departments' => $departments,
            'min_no' => $min_no,
        ]);
    }

    public function store(MaterialIssueNoteRequest $request)
    {
        DB::beginTransaction();

        $materialIssueNote = new MaterialIssueNote();
        $min_no = MaterialIssueNote::orderBy('id','desc')->limit(1)->value('min_no') > 0 ? MaterialIssueNote::orderBy('id','desc')->limit(1)->value('min_no') + 1 : date('Y').'1';
        $materialIssueNoteData = $request->only($materialIssueNote->getFillable());
        $materialIssueNoteData['min_no'] = $min_no;
        $materialIssueNoteData['created_by'] = auth()->id();
        $materialIssueNote = MaterialIssueNote::create($materialIssueNoteData);

        foreach($request->detail as $detail){
            $detail['material_issue_note_id'] = $materialIssueNote->id;
            MaterialIssueNoteDetail::create($detail);
        }

        $this->updateStock(null, 'store_stock');
        DB::commit();
        return redirect()->route('material-issue-notes.index');
    }


    public function edit(MaterialIssueNote $materialIssueNote)
    {
        $materialIssueNote->loadMissing(['detail','detail.product:id,name','detail.product.stock']);
        $materialIssueNote->date = date('Y-m-d', strtotime($materialIssueNote->date));
        foreach($materialIssueNote->detail as $detail){
            $detail->product->stock->current_stock = $detail->qty + $detail->product?->stock?->current_stock;
        }
        $products = Product::with('stock')->select('id', 'name')->whereStatus('active')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->whereType('inventory')->orderBy('name')->get();
        return Inertia::render('Inventory/MaterialIssueNote/Create', [
            'materialIssueNote' => $materialIssueNote,
            'products' => $products,
            'departments' => $departments,
            'min_no' => $materialIssueNote->min_no,
        ]);
    }

    public function update(MaterialIssueNoteRequest $request, MaterialIssueNote $materialIssueNote)
    {
        DB::beginTransaction();
        $materialIssueNoteData = $request->only($materialIssueNote->getFillable());
        $materialIssueNoteData['updated_by'] = auth()->id();
        $materialIssueNote->update($materialIssueNoteData);

        $deletedId = collect($request->detail)->pluck('id');
        MaterialIssueNoteDetail::whereMaterialIssueNoteId($materialIssueNote->id)->whereNotIn('id', $deletedId)->delete();
        foreach($request->detail as $detail){
            $detail['material_issue_note_id'] = $materialIssueNote->id;
            $materialIssueNoteDetail = new MaterialIssueNoteDetail();
            $materialIssueNoteDetailData = Arr::only($detail, $materialIssueNoteDetail->getFillable());
            if (isset($detail['id']) && $detail['id'] != '') {
                $materialIssueNoteDetail->whereId($detail['id'])->update($materialIssueNoteDetailData);
            } else {
                $materialIssueNoteDetail->create($materialIssueNoteDetailData);
            }
        }

        $this->updateStock(null, 'store_stock');
        DB::commit();


        return redirect()->route('material-issue-notes.index');
    }

    public function destroy(MaterialIssueNote $materialIssueNote)
    {
        MaterialIssueNoteDetail::whereMaterialIssueNoteId($materialIssueNote->id)->delete();
        $materialIssueNote->delete();
        $this->updateStock(null, 'store_stock');
        return Redirect::route('material-issue-notes.index');
    }
    public function print(MaterialIssueNote $materialIssueNote)
{
    $materialIssueNote->load(['detail','detail.product:id,name','detail.product.stock','department:id,name']);
    $user = \Auth::user()->name;
    $print_date_time = date('d-m-Y H:i:s');

    return Inertia::render('Inventory/MaterialIssueNote/MaterialIssuePrint', [
        'materialIssueNote' => $materialIssueNote,
        'user' => $user,
        'print_date_time' => $print_date_time,
    ]);
}

}
