<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductionController;

Route::post('login',[Controller::class, 'Login'])->name('Login');
Route::get('get',[Controller::class, 'get']);

Route::group( ['middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[Controller::class, 'logout']);

    //  drop down مواد للشراء
    Route::get('get-row-materials',[Controller::class, 'getRowMaterial']);

    // drop down منتجات للبيع
    Route::get('get-products',[Controller::class, 'getProducts']);

    //drop down أنواع منافذ البيع
    Route::get('get-selling-port-types',[Controller::class, 'getSellingPortType']);
    
    //استعراض وزن الشحنة بعد الوصول لكشف معين
    Route::get('get-weight-after-arrivel-detection/{recieptId}', [Controller::class, 'getWeightAfterArrival'])->middleware(['is-user-has-permission-to-read-poultry-detection','check-reciept-id', 'check-reciept-weighted']);
    //استعراض محتوى المخازن
    Route::get('display-warehouse-content',[Controller::class, 'displayWarehouseContent'])->middleware('has-display-warehouse-role');
    //استعراض الأوامر من مدير الإنتاج إلى المخازن
    Route::get('display-commands-to-warehouse',[ProductionController::class, 'displayCommandsToWarehouse'])->middleware('has-display-commands-warehouse-role');

    /////////////////////////// DROP DOWNS (DIRECTIONS)/////////////////
    Route::get('drop-down-from-lakes',[Controller::class, 'dropDownFromLake']);
    Route::get('drop-down-from-zero',[Controller::class, 'dropDownFromZero']);
    Route::get('drop-down-from-manufactoring',[Controller::class, 'dropDownFromManufactoring']);
    Route::get('drop-down-from-cutting',[Controller::class, 'dropDownFromCutting']);
    Route::get('drop-down-from-det1',[Controller::class, 'dropDownFromDet1']);
    Route::get('drop-down-from-det2',[Controller::class, 'dropDownFromDet2']);
    Route::get('drop-down-from-det3',[Controller::class, 'dropDownFromDet3']);
    
    
    
    
    
});
