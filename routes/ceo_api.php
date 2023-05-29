<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CEOController;
use App\Http\Controllers\SalesPurchasingRequestController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SlaughterSupervisorController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\ManufacturingController;


Route::post('login',[CEOController::class, 'CEOLogin'])->name('CEOLogin');
Route::group( ['prefix' => 'ceo','middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[CEOController::class, 'logout']);
    Route::get('ceck-dashboard',[CEOController::class, 'CEODashboard']);
    Route::get('ceck-ceo-role',[CEOController::class, 'checkCEORole']);
    Route::group( ['middleware' => 'is-request-exist'] ,function(){

    Route::post('accept-request/{RequestId}',[SalesPurchasingRequestController::class, 'acceptSalesPurchasingRequestFromCeo']);
    Route::post('refuse-request/{RequestId}',[SalesPurchasingRequestController::class, 'refuseSalesPurchasingRequestFromCeo']);

    });
    Route::get('display-request',[SalesPurchasingRequestController::class, 'displaySalesPurchasingRequestFromCeo']);
    //استعراض محتوى البحرات
    Route::get('display-lake-content',[WarehouseController::class, 'displayLakeContent']);
    //استعراض محتوى البراد الصفري
    Route::get('display-zero-frige-content',[WarehouseController::class, 'displayZeroFrigeContent']);
    //استعراض محتويات الصاعقة 1
    Route::get('display-det-1-content',[WarehouseController::class, 'displayDetonatorFrige1Content']);
    //استعراض محتويات الصاعقة 2
    Route::get('display-det-2-content',[WarehouseController::class, 'displayDetonatorFrige2Content']);
    //استعراض محتويات الصاعقة 3
    Route::get('display-det-3-content',[WarehouseController::class, 'displayDetonatorFrige3Content']);
    //استعراض محتويات المخزن النهائي
    Route::get('display-store-content',[WarehouseController::class, 'displayStoreContent']);
    //استعرض خرج الذبح
    Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter']);
    //استعراض خرج قسم التقطيع
    Route::get('display-output-cutting',[CuttingController::class, 'displayOutputCutting']);
    //استعراض خرج قسم التصنيع
    Route::get('display-output-munufacturing',[ManufacturingController::class, 'displayOutputManufacturing']);

    ////////////////////// استعراض managing level ////////////////////////
    Route::get('display-managing-level',[CEOController::class, 'getManagingLevel']);

    //إضافة مستخدم جديد
    Route::post('add-user',[CEOController::class, 'addUser']);
});

