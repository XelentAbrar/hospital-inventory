<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Traits\Stock;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepass;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\GoodsReceiptNote;
use XelentAbrar\HospitalInventory\Models\Inventory\Stock as InventoryStock;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\GoodsReceiptNoteRequest;

class GoodsReceiptNoteController extends Controller
{
    use Stock;

    public function index()
    {
        $goodsReceiptNotes = GoodsReceiptNote::with('inwardGatepass:id,gatepass_no')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/GoodsReceiptNote/Index', [
            'goodsReceiptNotes' => $goodsReceiptNotes,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $inwardGatepass = InwardGatepass::whereNotIn('id', GoodsReceiptNote::pluck('inward_gatepass_id'))->select('id', 'gatepass_no')->orderBy('gatepass_no')->get();
        $goods_receipt_no = GoodsReceiptNote::orderBy('id','desc')->limit(1)->value('goods_receipt_no') > 0 ? GoodsReceiptNote::orderBy('id','desc')->limit(1)->value('goods_receipt_no') + 1 : date('Y').'1';

        return Inertia::render('Inventory/GoodsReceiptNote/Create', [
            'csrf_token' => csrf_token(),
            'inwardGatepass' => $inwardGatepass,
            'goods_receipt_no' => $goods_receipt_no,
        ]);
    }

    public function store(GoodsReceiptNoteRequest $request)
    {
        DB::beginTransaction();

        $goodsReceiptNote = new GoodsReceiptNote();
        $goods_receipt_no = GoodsReceiptNote::orderBy('id','desc')->limit(1)->value('goods_receipt_no') > 0 ? GoodsReceiptNote::orderBy('id','desc')->limit(1)->value('goods_receipt_no') + 1 : date('Y').'1';
        $goodsReceiptNoteData = $request->only($goodsReceiptNote->getFillable());
        $goodsReceiptNoteData['goods_receipt_no'] = $goods_receipt_no;
        $goodsReceiptNoteData['created_by'] = auth()->id();
        $goodsReceiptNote = GoodsReceiptNote::create($goodsReceiptNoteData);

        $this->updateStock(null, 'total_qty');
        DB::commit();
        return redirect()->route('goods-receipt-notes.index');
    }


    public function edit(GoodsReceiptNote $goodsReceiptNote)
    {
        // $goodsReceiptNote->loadMissing(['detail','detail.product:id,name']);
        $inwardGatepass = InwardGatepass::whereNotIn('id', GoodsReceiptNote::whereNotIn('id', [$goodsReceiptNote->id])->pluck('inward_gatepass_id'))->select('id', 'gatepass_no')->orderBy('gatepass_no')->get();
        $goodsReceiptNote->date = date('Y-m-d', strtotime($goodsReceiptNote->date));
        return Inertia::render('Inventory/GoodsReceiptNote/Create', [
            'goodsReceiptNote' => $goodsReceiptNote,
            'inwardGatepass' => $inwardGatepass,
            'goods_receipt_no' => $goodsReceiptNote->goods_receipt_no,
        ]);
    }

    public function update(GoodsReceiptNoteRequest $request, GoodsReceiptNote $goodsReceiptNote)
    {
        DB::beginTransaction();
        $goodsReceiptNoteData = $request->only($goodsReceiptNote->getFillable());
        $goodsReceiptNoteData['updated_by'] = auth()->id();
        $goodsReceiptNote->update($goodsReceiptNoteData);

        $this->updateStock(null, 'total_qty');
        DB::commit();


        return redirect()->route('goods-receipt-notes.index');
    }

    public function destroy(GoodsReceiptNote $goodsReceiptNote)
    {
        $product_ids = $total_qty = [];
        foreach($goodsReceiptNote?->inwardGatepass?->detail as $detail){
            if(in_array($detail->product_id, $product_ids)){
                $index = array_search($detail->product_id, $product_ids);
                $total_qty[$index] += $detail->qty;
            }
            else{
                $product_ids[] = $detail->product_id;
                $total_qty[] = $detail->qty;
            }
        }

        foreach($product_ids as $index => $product_id){
            $stock = InventoryStock::whereProductId($product_id)->first();
            if($stock && $stock->total_qty < $total_qty[$index]){
                return Redirect::route('goods-receipt-notes.index');
            }
        }
        $this->updateStock(null, 'total_qty', $goodsReceiptNote->id);
        $goodsReceiptNote->delete();
        return Redirect::route('goods-receipt-notes.index');
    }
    public function print(GoodsReceiptNote $goodsReceiptNote)
    {
        // return  $goodsReceiptNote ;
        // $goodsReceiptNote = GoodsReceiptNote::get();
        $user = \Auth::user()->name;
        $print_date_time = date('d-m-Y H:i:s');

        return Inertia::render('Inventory/GoodsReceiptNote/ReceiptPrint', [
            'goodsReceiptNote' => $goodsReceiptNote,
            'user' => $user,
            'print_date_time' => $print_date_time,
        ]);
    }


}
