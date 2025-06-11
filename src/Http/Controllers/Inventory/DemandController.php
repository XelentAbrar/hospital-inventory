<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use App\Models\HRMS\Department;
use XelentAbrar\HospitalInventory\Models\Inventory\Demand;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\DemandDetail;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrderDetail;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\DemandRequest;

class DemandController extends Controller
{

    public function index()
    {
        $demands = Demand::with('department:id,name')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Demand/Index', [
            'demands' => $demands,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $products = Product::where('status','active')->select('id', 'name')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->whereType('inventory')->orderBy('name')->get();
        $demand_no = Demand::orderBy('id','desc')->limit(1)->value('demand_no') > 0 ? Demand::orderBy('id','desc')->limit(1)->value('demand_no') + 1 : date('Y').'1';

        return Inertia::render('Inventory/Demand/Create', [
            'csrf_token' => csrf_token(),
            'products' => $products,
            'departments' => $departments,
            'demand_no' => $demand_no,
        ]);
    }

    public function store(DemandRequest $request)
    {
        DB::beginTransaction();

        $demand = new Demand();
        $demand_no = Demand::orderBy('id','desc')->limit(1)->value('demand_no') > 0 ? Demand::orderBy('id','desc')->limit(1)->value('demand_no') + 1 : date('Y').'1';
        $demandData = $request->only($demand->getFillable());
        $demandData['demand_no'] = $demand_no;
        $demandData['created_by'] = auth()->id();
        $demand = Demand::create($demandData);

        foreach($request->detail as $detail){
            $detail['demand_id'] = $demand->id;
            DemandDetail::create($detail);
        }

        DB::commit();
        return redirect()->route('demands.index');
    }


    public function edit(Demand $demand)
    {
        $demand->loadMissing(['detail','detail.product:id,name']);
        $demand->date = date('Y-m-d', strtotime($demand->date));
        $products = Product::select('id', 'name')->where('status','active')->orderBy('name')->get();
        $demandIdUseInPo = PurchaseOrderDetail::select('demand_detail_id')->whereIn('demand_detail_id', $demand?->detail->pluck('id'))->pluck('demand_detail_id');
        $departments = Department::select('id', 'name')->whereType('inventory')->orderBy('name')->get();

        return Inertia::render('Inventory/Demand/Create', [
            'demand' => $demand,
            'products' => $products,
            'departments' => $departments,
            'demandIdUseInPo' => $demandIdUseInPo,
            'demand_no' => $demand->demand_no,
        ]);
    }

    public function update(DemandRequest $request, Demand $demand)
    {
        DB::beginTransaction();
        $demandData = $request->only($demand->getFillable());
        $demandData['updated_by'] = auth()->id();
        $demand->update($demandData);

        $deletedId = collect($request->detail)->pluck('id');
        DemandDetail::whereDemandId($demand->id)->whereNotIn('id', $deletedId)->delete();
        foreach($request->detail as $detail){
            $detail['demand_id'] = $demand->id;
            $demandDetail = new DemandDetail();
            $demandDetailData = Arr::only($detail, $demandDetail->getFillable());
            if (isset($detail['id']) && $detail['id'] != '') {
                $demandDetail->whereId($detail['id'])->update($demandDetailData);
            } else {
                $demandDetail->create($demandDetailData);
            }
        }

        DB::commit();


        return redirect()->route('demands.index');
    }

    public function destroy(Demand $demand)
    {
        $exists = PurchaseOrderDetail::select('demand_detail_id')->whereIn('demand_detail_id', $demand?->detail->pluck('id'))->exists();
        if(!$exists){
            DemandDetail::whereDemandId($demand->id)->delete();
            $demand->delete();
        }
        return Redirect::route('demands.index');
    }
    public function print(Demand $demand)
    {
        $demand->load(['detail','detail.product:id,name','department:id,name']);
        $user = \Auth::user()->name;
        $print_date_time = date('d-m-Y H:i:s');

        return Inertia::render('Inventory/Demand/DemandPrint', [
            'demands' => $demand,
            'user' => $user,
            'print_date_time' => $print_date_time,
        ]);


    }
}
