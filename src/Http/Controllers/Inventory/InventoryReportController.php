<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use XelentAbrar\HospitalInventory\Models\Inventory\Stock;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepass;
use XelentAbrar\HospitalInventory\Models\Inventory\GoodsReceiptNote;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNote;
use XelentAbrar\HospitalInventory\Models\Inventory\PurchaseOrderDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepassDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNoteDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialReturnNoteDetail;
use DateTime;

class InventoryReportController extends Controller
{

    public function ItemLedgerReport(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : null;
        $search = $request->search ?? '';
        $all_products = Product::where('status', 'active')->select('id','name')->get();
        if (!$search && !$from_date && !$to_date) {
            return Inertia::render('Inventory/Report/ItemLedger', [
                'reports' => [],
                'totalReport' => [
                    'total_opening_qty' => 0,
                    'total_purchase_qty' => 0,
                    'total_purchase_price' => 0,
                    'total_sale_qty' => 0,
                    'total_return_qty' => 0,
                    'current_qty' => 0
                ],
                'search' => '',
                'from_date' => null,
                'to_date' => null,
                'all_products' => $all_products,
            ]);
        }

        $query = Product::where('status', 'active');

        if ($search) {
            $query = $query->where('id', $search);
        }

         $products = $query->with([
            'inwardGatepassDetails.inwardGatepass' => function ($q) use ($from_date, $to_date) {
                if ($from_date && $to_date) {
                    $q->whereBetween('created_at', [$from_date, $to_date]);
                }
            },
            'materialIssueNoteDetails.materialIssueNote' => function ($q) use ($from_date, $to_date) {
                if ($from_date && $to_date) {
                    $q->whereBetween('created_at', [$from_date, $to_date]);
                }
            },
            'materialReturnNoteDetails.materialReturnNote' => function ($q) use ($from_date, $to_date) {
                if ($from_date && $to_date) {
                    $q->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        ])
            ->select('id', 'name', 'opening_stock', 'min_qty', 'max_qty')
            ->get();

        $report = $products->map(function ($product) use ($from_date, $to_date) {
            $inwardDetails = $product->inwardGatepassDetails->filter(function ($inward) use ($from_date, $to_date) {
                $createdAt = optional($inward->inwardGatepass)->created_at;
                return (!$from_date && !$to_date) ||
                    ($createdAt && Carbon::parse($createdAt)->between($from_date, $to_date));
            })->map(function ($inward, $index) {
                return [
                    'index' => $index + 1,
                    'gatepass_no' => optional($inward->inwardGatepass)->gatepass_no ?? 'N/A',
                    'supplier_name' => optional(optional($inward->inwardGatepass)->supplier)->name ?? 'N/A',
                    'qty' => $inward->qty,
                    'price' => $inward->price,
                    'tax_rate' => $inward->tax_rate ?? '',
                    'created_at' => optional($inward->inwardGatepass->created_at) ? $inward->inwardGatepass->created_at->format('d-m-Y') : 'N/A',
                ];
            })->filter(function ($detail) {
                return $detail['supplier_name'] !== 'N/A';
            });

            $issueDetails = $product->materialIssueNoteDetails->filter(function ($issue) use ($from_date, $to_date) {
                $createdAt = optional($issue->materialIssueNote)->created_at;
                return (!$from_date && !$to_date) ||
                    ($createdAt && Carbon::parse($createdAt)->between($from_date, $to_date));
            })->map(function ($issue) {
                return [
                    'min_no' => optional($issue->materialIssueNote)->min_no ?? 'N/A',
                    'department' => optional(optional($issue->materialIssueNote)->department)->name ?? 'N/A',
                    'qty' => $issue->qty,
                    'created_at' => optional($issue->materialIssueNote->created_at) ? $issue->materialIssueNote->created_at->format('d-m-Y') : 'N/A',
                ];
            });

            $returnDetails = $product->materialReturnNoteDetails->filter(function ($return) use ($from_date, $to_date) {
                $createdAt = optional($return->materialReturnNote)->created_at;
                return (!$from_date && !$to_date) ||
                    ($createdAt && Carbon::parse($createdAt)->between($from_date, $to_date));
            })->map(function ($return) {
                return [
                    'return_no' => optional($return->materialReturnNote)->return_no ?? 'N/A',
                    'qty' => $return->qty,
                    'created_at' => optional($return->materialReturnNote->created_at) ? $return->materialReturnNote->created_at->format('d-m-Y') : 'N/A',
                ];
            });

            return [
                'name' => $product->name,
                'opening_stock' => $product->opening_stock,
                'min_qty' => $product->min_qty,
                'max_qty' => $product->max_qty,
                'inward_details' => $inwardDetails,
                'issue_details' => $issueDetails,
                'return_details' => $returnDetails,
            ];
        })->filter(function ($product) {
            return $product['inward_details']->count() > 0 ||
                $product['issue_details']->count() > 0 ||
                $product['return_details']->count() > 0;
        });

        $totalOpeningStock = $products->sum('opening_stock');
        $totalPurchaseQty = $products->flatMap->inwardGatepassDetails->sum('qty');
        $totalPurchasePrice = $products->flatMap->inwardGatepassDetails->sum('price');
        $totalSaleQty = $products->flatMap->materialIssueNoteDetails->sum('qty');
        $totalReturnQty = $products->flatMap->materialReturnNoteDetails->sum('qty');

        $totalReport = [
            'total_opening_qty' => $totalOpeningStock,
            'total_purchase_qty' => $totalPurchaseQty,
            'total_purchase_price' => $totalPurchasePrice,
            'total_sale_qty' => $totalSaleQty,
            'total_return_qty' => $totalReturnQty,
            'current_qty' => $totalOpeningStock + $totalPurchaseQty + $totalReturnQty - $totalSaleQty
        ];

        return Inertia::render('Inventory/Report/ItemLedger', [
            'reports' => $report,
            'totalReport' => $totalReport,
            'search' => $search,
            'all_products' => $all_products,
            'from_date' => $from_date ? $from_date->format('Y-m-d') : null,
            'to_date' => $to_date ? $to_date->format('Y-m-d') : null,
        ]);
    }

    public function PurchaseReport(Request $request)
    {
        $query = InwardGatepassDetail::whereHas('product', function ($query) {
            $query->where('status', 'active');
        });

        // $purchaseDetails = $query->with('product')->get();


        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        if ($search) {

            $query->whereHas('inwardGatepass.supplier', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $reports = $query->whereHas('inwardGatepass', function ($query) use ($from_date, $to_date) {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        })
            ->with('inwardGatepass.supplier', 'product')
            ->get();
        return Inertia::render('Inventory/Report/PurchaseReport', [
            'reports' => $reports,
            'search' => $search,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }
    public function SaleReport(Request $request)
    {
        // $query = MaterialIssueNoteDetail::query();
        $query = MaterialIssueNoteDetail::whereHas('product', function ($query) {
            $query->where('status', 'active');
        });
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('materialIssueNote.department', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('product', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $reports = $query->whereHas('materialIssueNote', function ($query) use ($from_date, $to_date) {
            $query->whereBetween('date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        })
            ->with('materialIssueNote.department', 'product')
            ->get();
        return Inertia::render('Inventory/Report/SaleReport', [
            'reports' => $reports,
            'search' => $search,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }
    public function ReturnReport(Request $request)
    {
        // $query = MaterialReturnNoteDetail::query();
        $query = MaterialReturnNoteDetail::whereHas('product', function ($query) {
            $query->where('status', 'active');
        });
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $reports = $query->whereHas('materialReturnNote', function ($query) use ($from_date, $to_date) {
            $query->whereBetween('date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        })
            ->with('materialReturnNote', 'product')
            ->get();
        return Inertia::render('Inventory/Report/ReturnReport', [
            'reports' => $reports,
            'search' => $search,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }
    public function LowStockReport(Request $request)
    {
        // $query = Stock::query();
        $query = Stock::whereHas('product', function ($query) {
            $query->where('status', 'active');
        });
        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        if ($search) {
            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $reports = $query->with('product')->whereRaw('current_stock <= min_qty')->get();
        return Inertia::render('Inventory/Report/LowStock', [
            'reports' => $reports,
            'search' => $search,
        ]);
    }

    public function StockReport(Request $request)
    {
        $query = Stock::query();

        $from_date = Stock::min('created_at');
        $to_date = date('Y-m-d');
        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }

        if ($search) {
            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->where('status', 'active');
            });
        }

        // $reports = $query->join('products', 'stocks.product_id', '=', 'products.id')
        //     ->select('stocks.*', 'products.name as products_name')
        //     ->whereBetween('stocks.created_at', [$from_date, $to_date . ' 23:59:59'])
        //     ->where('stocks.current_Stock', '>', 0)
        //     ->orderByRaw('TRIM(products.name)')
        //     ->get();
        // $reports = $query->join('products', 'stocks.product_id', '=', 'products.id')
        // ->join('inward_gatepass_details', 'inward_gatepass_details.product_id', '=', 'products.id')
        // ->join('material_issue_note_details', 'material_issue_note_details.product_id', '=', 'products.id')
        // ->select('stocks.*', 'products.name as products_name', 'inward_gatepass_details.price as price','inward_gatepass_details.qty as qty','material_issue_note_details.qty as sold_qty')
        // ->whereBetween('stocks.created_at', [$from_date, $to_date . ' 23:59:59'])
        // ->where('stocks.current_Stock', '>', 0)
        // ->orderByRaw('TRIM(products.name)')
        // ->get();
        $reports = $query->join('products', 'stocks.product_id', '=', 'products.id')
        ->join('inward_gatepass_details', 'inward_gatepass_details.product_id', '=', 'products.id')
        ->join('material_issue_note_details', 'material_issue_note_details.product_id', '=', 'products.id')
        ->select(
            'stocks.product_id',
            'stocks.current_stock',
            'stocks.total_qty',
            'stocks.opening_stock',
            'stocks.used_stock',
            'stocks.return_stock',
            'stocks.min_qty',
            'products.name as products_name',
            DB::raw('AVG(inward_gatepass_details.price) as price'),

        )
        ->whereBetween('stocks.created_at', [$from_date, $to_date . ' 23:59:59'])
        ->where('stocks.current_Stock', '>', 0)
        ->groupBy('stocks.product_id', 'stocks.current_stock', 'stocks.total_qty','stocks.return_stock','stocks.used_stock','stocks.min_qty','stocks.opening_stock', 'products.name')
        ->orderByRaw('TRIM(products.name)')
        ->get();

        return Inertia::render('Inventory/Report/Stock', [
            'reports' => $reports,
            'search' => $search,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }


    public function ExpiryReport(Request $request)
    {
        $query = InwardGatepassDetail::whereHas('product', function ($query) {
            $query->where('status', 'active');
        });
        // $from_date = date('Y-m-01');
        // $to_date = date('Y-m-t');
        $from_date = Carbon::now()->startOfMonth();
        $to_date = Carbon::now();
        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }

        if ($search) {
            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $query->with('product')->whereDate('expiry_date', '>=', $from_date)->whereDate('expiry_date', '<=', $to_date)->orderBy('expiry_date', 'asc');

        $reports = $query->get();

        return Inertia::render('Inventory/Report/ExpiryProduct', [
            'reports' => $reports,
            'search' => $search,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function RateListReport(Request $request)
    {
        $query = PurchaseOrderDetail::query();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        if ($search) {
            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $query->whereBetween('purchase_order_details.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        // $reports = $query->with('product')->get();
        $reports = $query->join('products', 'purchase_order_details.product_id', '=', 'products.id')
            ->select('purchase_order_details.*', 'products.name as product_name')
            ->orderByRaw('TRIM(products.name)')
            ->get();
        return Inertia::render('Inventory/Report/RateList', [
            'reports' => $reports,
            'search' => $search,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function Inventorydashboard()
    {
        $totalMaterialIssueCount = MaterialIssueNote::count();
        $totalGoodReceiptCount = GoodsReceiptNote::count();

        // Inward gatepass details
        $inwardGatepassDetails = InwardGatepassDetail::with('product')->get();
        $totalInwardGatepassValue = $inwardGatepassDetails->sum(function ($detail) {
            return $detail->qty * $detail->price * (1 + $detail->tax_rate / 100);
        });

        $insulinProductNames = [
            'Insulin Card',
            'Penfill Insulin',
            'Disposable Insulin Syringe',
            'Insuline 70/30'
        ];

        $insulinProducts = Stock::with('product')
            ->whereHas('product', function ($query) use ($insulinProductNames) {
                $query->where('status', 'active')
                      ->whereIn('name', $insulinProductNames);
            })
            ->get(['product_id', 'min_qty', 'current_stock']);

        // dd($insulinProducts);


        $formattedInsulinProducts = $insulinProducts->map(function ($stock) {
            return [
                'product_id' => $stock->product_id,
                'product_name' => $stock->product->name,
                'min_qty' => $stock->min_qty,
                'current_stock' => $stock->current_stock,
            ];
        });

        $expiryProducts = $inwardGatepassDetails->filter(function ($detail) {
            return Carbon::parse($detail->expiry_date)->isFuture();
        });

        $monthWiseExpiry = [];
        foreach ($expiryProducts as $detail) {
            $expiryMonth = Carbon::parse($detail->expiry_date)->format('F Y');

            if (!isset($monthWiseExpiry[$expiryMonth])) {
                $monthWiseExpiry[$expiryMonth] = [];
            }
            $monthWiseExpiry[$expiryMonth][] = [
                'product_id' => $detail->product_id,
                'product_name' => $detail->product->name,
                'expiry_date' => $detail->expiry_date,
            ];
        }


        $data = [
            'inventory_dashboard' => [
                'total_material_issue_count' => $totalMaterialIssueCount,
                'total_good_receipt_count' => $totalGoodReceiptCount,
                'total_inward_gatepass_value' => $totalInwardGatepassValue,
                'insulin_products' => $formattedInsulinProducts,
                'month_wise_expiry' => $monthWiseExpiry,
            ]
        ];

        return Inertia::render('InventoryDashboard', $data);
    }
}
