<?php

use App\Http\Controllers\HRMS\DepartmentController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\BrandController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\CustomerController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\DemandController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\GoodsReceiptNoteController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\InventoryReportController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\InwardGatepassController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\ItemCategoryController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\ItemTypeController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\MaterialIssueNoteController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\MaterialReturnNoteController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\ProductController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\PurchaseOrderController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\SaltController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\SubCategoryController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\SupplierController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\TaxController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\UomController;
use XelentAbrar\HospitalInventory\Http\Controllers\Inventory\WarehouseController;
use Illuminate\Support\Facades\Route;





Route::resource('brands', BrandController::class)->names('brands');

Route::resource('customers', CustomerController::class)->names('customers');

Route::resource('products', ProductController::class)->names('products');

Route::resource('demands', DemandController::class)->names('demands');
Route::get('demands/print/{demand}', [DemandController::class, 'print'])->name('demands.print');

Route::resource('purchase-orders', PurchaseOrderController::class)->names('purchase-orders');
Route::get('purchase-orders/print/{purchaseOrder}', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');

Route::resource('inward-gatepass', InwardGatepassController::class)->names('inward-gatepass');
Route::get('inward-gatepass/print/{inwardGatepas}', [InwardGatepassController::class, 'print'])->name('inward-gatepass.print');

Route::resource('goods-receipt-notes', GoodsReceiptNoteController::class)->names('goods-receipt-notes');
Route::get('goods-receipt-notes/print/{goodsReceiptNote}', [GoodsReceiptNoteController::class, 'print'])->name('goods-receipt-notes.print');

Route::resource('material-issue-notes', MaterialIssueNoteController::class)->names('material-issue-notes');
Route::get('material-issue-notes/print/{materialIssueNote}', [MaterialIssueNoteController::class, 'print'])->name('material-issue-notes.print');

Route::resource('material-return-notes', MaterialReturnNoteController::class)->names('material-return-notes');
Route::get('material-return-notes/print/{materialReturnNote}', [MaterialReturnNoteController::class, 'print'])->name('material-return-notes.print');

Route::resource('sub-categories', SubCategoryController::class)->names('sub-categories');

Route::resource('suppliers', SupplierController::class)->names('suppliers');

Route::resource('taxes', TaxController::class)->names('taxes');
Route::resource('salts', SaltController::class)->names('salts');
Route::resource('item_types', ItemTypeController::class)->names('item_types');
Route::resource('item_categories', ItemCategoryController::class)->names('item_categories');

Route::resource('uoms', UomController::class)->names('uoms');

Route::resource('warehouses', WarehouseController::class)->names('warehouses');

Route::get('/inventory-departments/create', [DepartmentController::class, 'inventoryCreate'])->name('inventory-departments.create');
Route::get('/inventory-departments', [DepartmentController::class, 'inventoryIndex'])->name('inventory-departments.index');
Route::get('/inventory/low_stock',[InventoryReportController::class, 'LowStockReport'])->name('inventory.low-stock');
Route::get('/inventory/stock',[InventoryReportController::class, 'StockReport'])->name('inventory.stock');
Route::get('/inventory/expiry_products',[InventoryReportController::class, 'ExpiryReport'])->name('inventory.expiry-product');
Route::get('/inventory/rate_list',[InventoryReportController::class, 'RateListReport'])->name('inventory.rate-list');
Route::get('/inventory/item_ledger',[InventoryReportController::class, 'ItemLedgerReport'])->name('inventory.item-ledger');
Route::get('/inventory/purchase_report',[InventoryReportController::class, 'PurchaseReport'])->name('inventory.purchase-report');
Route::get('/inventory/sale_report',[InventoryReportController::class, 'SaleReport'])->name('inventory.sale-report');
Route::get('/inventory/return_report',[InventoryReportController::class, 'ReturnReport'])->name('inventory.return-report');
// Route::get('/dashboard',[InventoryReportController::class, 'Inventorydashboard'])->name('dashboard');
