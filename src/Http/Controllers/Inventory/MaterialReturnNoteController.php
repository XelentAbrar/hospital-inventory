<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Traits\Stock;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialReturnNote;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialReturnNoteDetail;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\MaterialReturnNoteRequest;

class MaterialReturnNoteController extends Controller
{
    use Stock;

    public function index()
    {
        $materialReturnNotes = MaterialReturnNote::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/MaterialReturnNote/Index', [
            'materialReturnNotes' => $materialReturnNotes,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $products = Product::with('stock')->whereStatus('active')->select('id', 'name')->orderBy('name')->get();
        $mrn_no = MaterialReturnNote::orderBy('id','desc')->limit(1)->value('mrn_no') > 0 ? MaterialReturnNote::orderBy('id','desc')->limit(1)->value('mrn_no') + 1 : date('Y').'1';

        return Inertia::render('Inventory/MaterialReturnNote/Create', [
            'csrf_token' => csrf_token(),
            'products' => $products,
            'mrn_no' => $mrn_no,
        ]);
    }

    public function store(MaterialReturnNoteRequest $request)
    {
        DB::beginTransaction();

        $materialReturnNote = new MaterialReturnNote();
        $mrn_no = MaterialReturnNote::orderBy('id','desc')->limit(1)->value('mrn_no') > 0 ? MaterialReturnNote::orderBy('id','desc')->limit(1)->value('mrn_no') + 1 : date('Y').'1';
        $materialReturnNoteData = $request->only($materialReturnNote->getFillable());
        $materialReturnNoteData['mrn_no'] = $mrn_no;
        $materialReturnNoteData['created_by'] = auth()->id();
        $materialReturnNote = MaterialReturnNote::create($materialReturnNoteData);

        foreach($request->detail as $detail){
            $detail['material_return_note_id'] = $materialReturnNote->id;
            MaterialReturnNoteDetail::create($detail);
        }

        $this->updateStock(null, 'return_stock');
        DB::commit();
        return redirect()->route('material-return-notes.index');
    }


    public function edit(MaterialReturnNote $materialReturnNote)
    {
        $materialReturnNote->loadMissing(['detail','detail.product:id,name','detail.product.stock']);
        $materialReturnNote->date = date('Y-m-d', strtotime($materialReturnNote->date));
        foreach($materialReturnNote->detail as $detail){
            $detail->product->stock->current_stock = $detail->qty + $detail->product?->stock?->current_stock;
        }
        $products = Product::with('stock')->select('id', 'name')->whereStatus('active')->orderBy('name')->get();
        return Inertia::render('Inventory/MaterialReturnNote/Create', [
            'materialReturnNote' => $materialReturnNote,
            'products' => $products,
            'mrn_no' => $materialReturnNote->mrn_no,
        ]);
    }

    public function update(MaterialReturnNoteRequest $request, MaterialReturnNote $materialReturnNote)
    {
        DB::beginTransaction();
        $materialReturnNoteData = $request->only($materialReturnNote->getFillable());
        $materialReturnNoteData['updated_by'] = auth()->id();
        $materialReturnNote->update($materialReturnNoteData);

        $deletedId = collect($request->detail)->pluck('id');
        MaterialReturnNoteDetail::whereMaterialReturnNoteId($materialReturnNote->id)->whereNotIn('id', $deletedId)->delete();
        foreach($request->detail as $detail){
            $detail['material_return_note_id'] = $materialReturnNote->id;
            $materialReturnNoteDetail = new MaterialReturnNoteDetail();
            $materialReturnNoteDetailData = Arr::only($detail, $materialReturnNoteDetail->getFillable());
            if (isset($detail['id']) && $detail['id'] != '') {
                $materialReturnNoteDetail->whereId($detail['id'])->update($materialReturnNoteDetailData);
            } else {
                $materialReturnNoteDetail->create($materialReturnNoteDetailData);
            }
        }

        $this->updateStock(null, 'return_stock');
        DB::commit();


        return redirect()->route('material-return-notes.index');
    }

    public function destroy(MaterialReturnNote $materialReturnNote)
    {
        MaterialReturnNoteDetail::whereMaterialReturnNoteId($materialReturnNote->id)->delete();
        $materialReturnNote->delete();
        $this->updateStock(null, 'return_stock');
        return Redirect::route('material-return-notes.index');
    }
    public function print(MaterialReturnNote $materialReturnNote)
    {
        $materialReturnNote->load(['detail','detail.product:id,name','detail.product.stock']);
        $user = \Auth::user()->name;
        $print_date_time = date('d-m-Y H:i:s');

        return Inertia::render('Inventory/MaterialReturnNote/ReturnPrint', [
            'materialReturnNote' => $materialReturnNote,
            'user' => $user,
            'print_date_time' => $print_date_time,
        ]);
    }
}
