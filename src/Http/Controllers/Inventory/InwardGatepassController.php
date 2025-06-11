<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use XelentAbrar\HospitalInventory\Models\Inventory\Tax;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use XelentAbrar\HospitalInventory\Models\Inventory\Supplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrder;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepass;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\GoodsReceiptNote;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrderDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepassDetail;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\InwardGatepassRequest;

class InwardGatepassController extends Controller
{

    public function index()
    {
        $inwardGatepass = InwardGatepass::with('supplier')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/InwardGatepass/Index', [
            'inwardGatepass' => $inwardGatepass,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $products = Product::whereStatus('active')->select('id', 'name')->orderBy('name')->get();
        $purchase_orders = PurchaseOrder::with(['detail.tax:id,name,rate','detail.product:id,name,uom_id','detail.product.uom:id,name'])->orderBy('id','desc')->get();
        $taxes = Tax::whereStatus('active')->orderBy('id','desc')->get();
        $purchase_order_details = $this->purchaseOrderDetailWithQty($purchase_orders);
        $gatepass_no = InwardGatepass::orderBy('id','desc')->limit(1)->value('gatepass_no') > 0 ? InwardGatepass::orderBy('id','desc')->limit(1)->value('gatepass_no') + 1 : date('Y').'1';
        $suppliers = Supplier::select('id','name')->orderBy('id','desc')->get();
        return Inertia::render('Inventory/InwardGatepass/Create', [
            'csrf_token' => csrf_token(),
            'products' => $products,
            'purchase_orders' => $purchase_orders,
            'suppliers' => $suppliers,
            'purchase_order_details' => $purchase_order_details,
            'gatepass_no' => $gatepass_no,
            'taxes' => $taxes,
        ]);
    }

    public function store(InwardGatepassRequest $request)
    {
        DB::beginTransaction();

        $inwardGatepass = new InwardGatepass();
        $gatepass_no = InwardGatepass::orderBy('id','desc')->limit(1)->value('gatepass_no') > 0 ? InwardGatepass::orderBy('id','desc')->limit(1)->value('gatepass_no') + 1 : date('Y').'1';
        $inwardGatepassData = $request->only($inwardGatepass->getFillable());
        $inwardGatepassData['gatepass_no'] = $gatepass_no;
        $inwardGatepassData['created_by'] = auth()->id();
        $inwardGatepass = InwardGatepass::create($inwardGatepassData);

        foreach($request->detail as $detail){
            $detail['inward_gatepass_id'] = $inwardGatepass->id;
            $detail['tax_rate'] = Tax::whereId($detail['tax_id'])->value('rate');
            InwardGatepassDetail::create($detail);
        }

        DB::commit();
        return redirect()->route('inward-gatepass.index');
    }


    public function edit(InwardGatepass $inwardGatepass)
    {
        $inwardGatepass->loadMissing(['detail.tax','detail.purchaseOrderDetail.product:id,name,uom_id','detail.purchaseOrderDetail.product.uom:id,abrv']);
        $inwardGatepass->date = date('Y-m-d', strtotime($inwardGatepass->date));
        $taxes = Tax::whereStatus('active')->orderBy('id','desc')->get();
        $purchase_orders = PurchaseOrder::with('detail.tax:id,name,rate')->orderBy('id','desc')->get();
        foreach($inwardGatepass->detail as $detail){
            $qty = InwardGatepassDetail::wherePurchaseOrderDetailId($detail->purchase_order_detail_id)->whereNotIn('id', [$detail->id])->sum('qty');
            $purchaseOrderQty = PurchaseOrderDetail::whereId($detail->purchase_order_detail_id)->value('qty');
            $detail->purchaseOrderDetail['display_product_name'] = $detail->purchaseOrderDetail?->purchaseOrder?->po_no .' '. $detail->purchaseOrderDetail?->product?->name;
            $detail->purchaseOrderDetail['available_qty'] = $purchaseOrderQty - $qty;
        }
        $purchase_order_details = $this->purchaseOrderDetailWithQty($purchase_orders);
        $suppliers = Supplier::select('id','name')->orderBy('id','desc')->get();
        $goodsReceiptNotesIssue = GoodsReceiptNote::where('inward_gatepass_id', $inwardGatepass->id)->exists();
        return Inertia::render('Inventory/InwardGatepass/Create', [
            'inwardGatepass' => $inwardGatepass,
            'purchase_order_details' => $purchase_order_details,
            'purchase_orders' => $purchase_orders,
            'suppliers' => $suppliers,
            'taxes' => $taxes,
            'gatepass_no' => $inwardGatepass->gatepass_no,
            'goodsReceiptNotesIssue' => $goodsReceiptNotesIssue,
        ]);
    }

    public function update(InwardGatepassRequest $request, InwardGatepass $inwardGatepass)
    {
        DB::beginTransaction();

        $inwardGatepassData = $request->only($inwardGatepass->getFillable());
        $inwardGatepassData['updated_by'] = auth()->id(); 
        $inwardGatepass->update($inwardGatepassData);

        $deletedId = collect($request->detail)->pluck('id');
        InwardGatepassDetail::whereInwardGatepassId($inwardGatepass->id)->whereNotIn('id', $deletedId)->delete();
        foreach($request->detail as $detail){
            $detail['inward_gatepass_id'] = $inwardGatepass->id;
            $detail['tax_rate'] = Tax::whereId($detail['tax_id'])->value('rate');
            $inwardGatepassDetail = new InwardGatepassDetail();
            $inwardGatepassDetailData = Arr::only($detail, $inwardGatepassDetail->getFillable());
            if (isset($detail['id']) && $detail['id'] != '') {
                $inwardGatepassDetail->whereId($detail['id'])->update($inwardGatepassDetailData);
            } else {
                $inwardGatepassDetail->create($inwardGatepassDetailData);
            }

        }

        DB::commit();


        return redirect()->route('inward-gatepass.index');
    }

    public function destroy(InwardGatepass $inwardGatepass)
    {
        if(!GoodsReceiptNote::where('inward_gatepass_id', $inwardGatepass->id)->exists()){
            InwardGatepassDetail::whereInwardGatepassId($inwardGatepass->id)->delete();
            $inwardGatepass->delete();
        }
        return Redirect::route('inward-gatepass.index');
    }

    public function purchaseOrderDetailWithQty($purchase_orders, $ids = null)
    {
        $purchase_order_detail = [];
        foreach($purchase_orders as $purchase_order){
            foreach($purchase_order->detail as $detail){
                $qty = InwardGatepassDetail::wherePurchaseOrderDetailId($detail->id);
                if($ids){
                    $qty = $qty->whereNotIn('id', $ids);
                }
                $qty = $qty->sum('qty');
                $detail['display_product_name'] = $purchase_order->po_no .' '.(isset($detail->product->name) ? $detail->product->name : null);
                $detail['available_qty'] = $detail->qty - $qty;
                $detail['product'] = $detail->product;
                $detail['product']['uom'] = $detail->product?->uom;
                $purchase_order_detail[] = $detail;
            }
        }
        return $purchase_order_detail;
    }
    public function print(InwardGatepass $inwardGatepas)
    {
        $inwardGatepas->load('detail.tax','detail.product:id,name','supplier:id,name','detail.purchaseOrderDetail.product:id,name,uom_id','detail.purchaseOrderDetail.product.uom:id,abrv');
        $user = \Auth::user()->name;
        $print_date_time = date('d-m-Y H:i:s');
        return Inertia::render('Inventory/InwardGatepass/InwardPrint', [
            'inwardGatepass' => $inwardGatepas,
            'user' => $user,
            'print_date_time' => $print_date_time,
        ]);
    }
}
