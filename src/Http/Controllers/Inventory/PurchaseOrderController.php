<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use XelentAbrar\HospitalInventory\Models\Inventory\Tax;
use XelentAbrar\HospitalInventory\Models\Inventory\Demand;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use XelentAbrar\HospitalInventory\Models\Inventory\Supplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\DemandDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrder;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrderDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepassDetail;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\PurchaseOrderRequest;

class PurchaseOrderController extends Controller
{

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/PurchaseOrder/Index', [
            'purchaseOrders' => $purchaseOrders,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $products = Product::whereStatus('active')->select('id', 'name')->orderBy('name')->get();
        $demands = Demand::with(['detail.product:id,name,uom_id','detail.product.uom:id,name'])->orderBy('id','desc')->get();
        $taxes = Tax::whereStatus('active')->orderBy('id','desc')->get();
        $demand_details = $this->demandDetailWithQty($demands);
        $po_no = PurchaseOrder::orderBy('id','desc')->limit(1)->value('po_no') > 0 ? PurchaseOrder::orderBy('id','desc')->limit(1)->value('po_no') + 1 : date('Y').'1';
        $suppliers = Supplier::select('id','name')->orderBy('id','desc')->get();
        return Inertia::render('Inventory/PurchaseOrder/Create', [
            'csrf_token' => csrf_token(),
            'products' => $products,
            'demands' => $demands,
            'suppliers' => $suppliers,
            'demand_details' => $demand_details,
            'po_no' => $po_no,
            'taxes' => $taxes,
        ]);
    }

    public function store(PurchaseOrderRequest $request)
    {
        DB::beginTransaction();

        $purchaseOrder = new PurchaseOrder();
        $po_no = PurchaseOrder::orderBy('id','desc')->limit(1)->value('po_no') > 0 ? PurchaseOrder::orderBy('id','desc')->limit(1)->value('po_no') + 1 : date('Y').'1';
        $purchaseOrderData = $request->only($purchaseOrder->getFillable());
        $purchaseOrderData['po_no'] = $po_no;
        $purchaseOrderData['created_by'] = auth()->id();
        $purchaseOrder = PurchaseOrder::create($purchaseOrderData);

        foreach($request->detail as $detail){
            $detail['purchase_order_id'] = $purchaseOrder->id;
            if(isset($detail['tax_id']) && Tax::whereId($detail['tax_id'])->exists()) {
                $detail['tax_rate'] = Tax::whereId($detail['tax_id'])->value('rate');
            } else {
                $detail['tax_rate'] = null;
            }
            PurchaseOrderDetail::create($detail);
        }

        DB::commit();
        return redirect()->route('purchase-orders.index');
    }


    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->loadMissing(['detail.tax','detail.demandDetail.product:id,name,uom_id','detail.demandDetail.product.uom:id,abrv']);
        $purchaseOrder->date = date('Y-m-d', strtotime($purchaseOrder->date));
        $taxes = Tax::whereStatus('active')->orderBy('id','desc')->get();
        $demands = Demand::with('detail')->orderBy('id','desc')->get();
        foreach($purchaseOrder->detail as $detail){
            $qty = PurchaseOrderDetail::whereDemandDetailId($detail->demand_detail_id)->whereNotIn('id', [$detail->id])->sum('qty');
            $demandQty = DemandDetail::whereId($detail->demand_detail_id)->value('qty');
            $detail->demandDetail['display_product_name'] = $detail->demandDetail?->demand?->demand_no .' '. $detail->demandDetail?->product?->name;
            $detail->demandDetail['available_qty'] = $demandQty - $qty;
        }
        $demand_details = $this->demandDetailWithQty($demands);
        $suppliers = Supplier::select('id','name')->orderBy('id','desc')->get();
        $purchaseOrderIdUseInPo = InwardGatepassDetail::select('purchase_order_detail_id')->whereIn('purchase_order_detail_id', $purchaseOrder?->detail->pluck('id'))->pluck('purchase_order_detail_id');
        return Inertia::render('Inventory/PurchaseOrder/Create', [
            'purchaseOrder' => $purchaseOrder,
            'demand_details' => $demand_details,
            'demands' => $demands,
            'suppliers' => $suppliers,
            'taxes' => $taxes,
            'purchaseOrderIdUseInPo' => $purchaseOrderIdUseInPo,
            'po_no' => $purchaseOrder->po_no,
        ]);
    }

    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        DB::beginTransaction();

        $purchaseOrderData = $request->only($purchaseOrder->getFillable());
        $purchaseOrderData['updated_by'] = auth()->id();
        $purchaseOrder->update($purchaseOrderData);

        $deletedId = collect($request->detail)->pluck('id');
        PurchaseOrderDetail::wherePurchaseOrderId($purchaseOrder->id)->whereNotIn('id', $deletedId)->delete();
        foreach($request->detail as $detail){
            $detail['purchase_order_id'] = $purchaseOrder->id;
            $detail['tax_rate'] = Tax::whereId($detail['tax_id'])->value('rate');
            $purchaseOrderDetail = new PurchaseOrderDetail();
            $purchaseOrderDetailData = Arr::only($detail, $purchaseOrderDetail->getFillable());
            if (isset($detail['id']) && $detail['id'] != '') {
                $purchaseOrderDetail->whereId($detail['id'])->update($purchaseOrderDetailData);
            } else {
                $purchaseOrderDetail->create($purchaseOrderDetailData);
            }

        }

        DB::commit();


        return redirect()->route('purchase-orders.index');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $exists = InwardGatepassDetail::select('purchase_order_detail_id')->whereIn('purchase_order_detail_id', $purchaseOrder?->detail->pluck('id'))->exists();
        if(!$exists){
            PurchaseOrderDetail::wherePurchaseOrderId($purchaseOrder->id)->delete();
            $purchaseOrder->delete();
        }
        return Redirect::route('purchase-orders.index');
    }

    public function demandDetailWithQty($demands, $ids = null)
    {
        $demand_detail = [];
        foreach($demands as $demand){
            foreach($demand->detail as $detail){
                $qty = PurchaseOrderDetail::whereDemandDetailId($detail->id);
                if($ids){
                    $qty = $qty->whereNotIn('id', $ids);
                }
                $qty = $qty->sum('qty');
                $detail['display_product_name'] = $demand->demand_no .' '.(isset($detail->product->name) ? $detail->product->name : null);
                $detail['available_qty'] = $detail->qty - $qty;
                $detail['product'] = $detail->product;
                $detail['product']['uom'] = $detail->product?->uom;
                $demand_detail[] = $detail;
            }
        }
        return $demand_detail;
    }
    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['detail.tax','detail.product','supplier','detail.demandDetail.product:id,name,uom_id','detail.demandDetail.product.uom:id,abrv']);
        $user = \Auth::user()->name;
        $print_date_time = date('d-m-Y H:i:s');
        return Inertia::render('Inventory/PurchaseOrder/OrderPrint', [
            'purchaseOrders' => $purchaseOrder,
            'user' => $user,
            'print_date_time' => $print_date_time,
        ]);
    }
}
