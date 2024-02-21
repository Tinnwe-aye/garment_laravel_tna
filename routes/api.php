<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Raw\RawMaterialController;
use App\Http\Controllers\API\Customer\CustomerController;
use App\Http\Controllers\API\Supplier\SupplierController;
use App\Http\Controllers\API\Tailor\TailorListController;
use App\Http\Controllers\API\Township\TownshipController;
use App\Http\Controllers\API\Product\ProductListController;
use App\Http\Controllers\API\Product\ProductCategoryController;
use App\Http\Controllers\API\Product\ProductInController;
use App\Http\Controllers\API\Tailor\TailorRegistrationController;
use App\Http\Controllers\API\CustomerTransaction\CustomerTransactionController;
use App\Http\Controllers\API\SupplierTransaction\SupplierTransactionController;
use App\Http\Controllers\API\SupplierTransaction\SupplierTransactionListController;
use App\Http\Controllers\API\Size\SizesController;
use App\Http\Controllers\API\TailorRaw\TailorRawController;
use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('tailor-list')->group(function () {
    Route::get('getTailorAll',[TailorListController::class, 'index']);
    Route::get('search-tailor',[TailorListController::class, 'search']);
    Route::post('delete-tailor',[TailorListController::class, 'destroy']);
});
Route::prefix('tailor-register')->group(function () {
    Route::post('register-tailor',[TailorRegistrationController::class, 'store']);
    Route::get('edit-tailor/{id}',[TailorRegistrationController::class, 'show']);
    Route::put('update-tailor/{id}',[TailorRegistrationController::class, 'update']);
});

Route::prefix('product-in')->group(function () {
    Route::post('store',[ProductInController::class, 'store']);
    Route::get('edit/{id}',[ProductInController::class, 'edit']);
    Route::put('update/{id}',[ProductInController::class, 'update']);
    Route::post('destroy',[ProductInController::class, 'destroy']);
});
Route::prefix('product-in-list')->group(function () {
    Route::get('getProductAll',[ProductListController::class, 'index']);
    Route::get('searchTailor',[ProductListController::class, 'searchTailor']);
    Route::get('searchTailorByID',[ProductListController::class, 'searchTailorByID']);
    Route::post('search-product',[ProductListController::class, 'search']);
});

Route::prefix('raws')->group(function () {
    Route::post('raw-register',[RawMaterialController::class, 'store']);
    Route::get('raw-search',[RawMaterialController::class, 'index']);
    Route::post('raw-delete',[RawMaterialController::class, 'destroy']);
    Route::post('raw-edit/{id}',[RawMaterialController::class, 'show']);
    Route::put('raw-update/{id}',[RawMaterialController::class, 'update']);
});

Route::prefix('supplier')->group(function () {
    Route::post('create',[SupplierController::class, 'saveSupplier']);
    Route::get('retrieve',[SupplierController::class, 'getSupplierList']);
    Route::post('update',[SupplierController::class, 'editSupplier']);
    Route::post('delete',[SupplierController::class, 'removeSupplier']);
});

Route::prefix('customer')->group(function () {
    Route::post('storeCustomer',[CustomerController::class, 'store']);
    Route::get('getCustomerList',[CustomerController::class, 'show']);
    Route::get('editCustomer/{id}',[CustomerController::class, 'edit']);
    Route::put('updateCustomer/{id}',[CustomerController::class, 'update']);
    Route::post('deleteCustomer',[CustomerController::class, 'destroy']);
    Route::get('getCustomerId',[CustomerController::class, 'getCustomerId']);
    // Route::get('getAllCustomerId',[CustomerController::class, 'getAllCustomerId']);
});

Route::prefix('township')->group(function () {
    Route::get('getTownship',[TownshipController::class, 'index']);
    Route::post('storeTownship',[TownshipController::class, 'store']);
});

Route::prefix('supplier-transaction')->group(function () {
    Route::post('store-supplier-transaction',[SupplierTransactionController::class, 'store']);
   
});
Route::prefix('supplier-transaction-list')->group(function () {
    Route::post('search-supplier-transaction',[SupplierTransactionListController::class, 'search']);
    Route::post('delete-supplier-transaction',[SupplierTransactionListController::class, 'destroy']);
   
});

Route::prefix('product-list')->group(function () {
    Route::get('get-product-names',[ProductListController::class, 'getProductNames']);
    Route::post('get-product-sizes-by-name',[ProductListController::class, 'getProductSizeByName']);   
});

Route::prefix('product')->group(function () {
    Route::post('create',[ProductCategoryController::class, 'create']);
    Route::post('createProductSize',[ProductCategoryController::class, 'createProductSize']);
});

Route::prefix('customer-transaction-register')->group(function () {
    Route::post('save',[CustomerTransactionController::class, 'store']);
    Route::put('update/{id}',[CustomerTransactionController::class, 'update']);
   
});

Route::prefix('customer-transaction-list')->group(function () {
    Route::post('delete',[CustomerTransactionController::class, 'destroy']);
    Route::post('search',[CustomerTransactionController::class, 'show']);
    Route::get('edit/{id}',[CustomerTransactionController::class, 'edit']);
    Route::post('destroy',[CustomerTransactionController::class, 'destroy']);
});

Route::prefix('sizes')->group(function () {
    Route::get('getsizes',[SizesController::class, 'index']);
});

Route::prefix('categories')->group(function () {
    Route::get('getCategory',[CategoryController::class, 'index']);
});

Route::prefix('tailor-raw')->group(function () {
    Route::post('createTailorRawTransaction',[TailorRawController::class, 'create']);
    Route::post('search',[TailorRawController::class, 'search']);
    Route::post('store',[TailorRawController::class, 'store']);
    Route::get('edit/{id}',[TailorRawController::class, 'edit']);
    Route::put('update/{id}',[TailorRawController::class, 'update']);
    Route::post('destroy',[TailorRawController::class, 'destroy']);
    Route::post('searchTailorRaw',[TailorRawController::class,   'searchTailorRaw']);
});

Route::prefix('users')->group(function () { 
    Route::post('save',[UserController::class,'store']);
    Route::post('login',[UserController::class,'login']);
});




