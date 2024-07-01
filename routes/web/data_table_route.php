<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ManageRoleController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AttributeSetController;
use App\Http\Controllers\SpecialOccasionController;
use App\Http\Controllers\SubscriptionController;

Route::group(
    [
        'middleware' => ['auth'],
        'prefix' => 'dataTable'
    ],
    function () {
        Route::get('/admin-list-table', [AdminController::class, 'dataTableAdminsListTable'])->name('dataTable.dataTableAdminsListTable');
        Route::get('/user-list-table', [ManageUserController::class, 'dataTableUsersListTable'])->name('dataTable.dataTableUsersListTable');
        Route::get('/attributes-list-table', [AttributeController::class, 'dataTableAtttributesTable'])->name('dataTable.dataTableProductAtttributesTable');
        Route::get('/category-table', [CategoryController::class, 'dataTablecategory'])->name('dataTable.dataTablecategoriestable');
        Route::get('/products-table', [ProductController::class, 'dataTableproducts'])->name('dataTable.dataTableProductTable');
        Route::get('/order-list', [OrdersController::class, 'dataTableUserOrderList'])->name('dataTable.dataTableOrderList');
        // Route::get('/order-list-report', [ReportController::class, 'dataTableOrderListReport'])->name('dataTable.dataTableOrderListReport');
        Route::get('/order-items-list/{id}', [OrdersController::class, 'dataTableUserOrderItemList'])->name('dataTable.dataTableOrderItemsList');
        Route::get('/all-orderlistAdmin', [OrdersController::class, 'dataTableAdminOrderList'])->name('dataTable.dataTableAdminOrderList');
        Route::get('/all-orderlist', [HomeController::class, 'dataTableOrderListDashoard'])->name('dataTable.dataTableOrderListDashoard');
        Route::get('/roles-list-table', [ManageRoleController::class, 'dataTableRolesListTable'])->name('dataTable.dataTableRolesListTable');
        Route::get('/subscription-list-table',[SubscriptionController::class,'subscriptiondatatable'])->name('dataTable.subscriptiondatatable');
    }

);
