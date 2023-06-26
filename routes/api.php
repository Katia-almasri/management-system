<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SlaughterSupervisorController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\ManufacturingController;
use App\Http\Controllers\WarehouseController;

Route::post('login',[Controller::class, 'Login'])->name('Login');
Route::get('get',[Controller::class, 'get']);

Route::group( ['middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[Controller::class, 'logout']);

    //  drop down مواد للشراء
    Route::get('get-row-materials',[Controller::class, 'getRowMaterial']);

     //  drop down ملء أمر إنتاج
     Route::get('get-production-command',[Controller::class, 'getProductionCommandsDropDown']);

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

    ////////////////////عرض خرج الذبح////////////////////////
    Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter'])->middleware('check-read-output-slaughter');
    /////////////////عرض خرج التقطيع/////////////////////
    Route::get('display-output-cutting',[CuttingController::class, 'displayOutputCutting'])->middleware('check-read-output-cutting');
    //////////////////عرض خرج التصنيع////////////////////////
    Route::get('display-output-manufacturing',[ManufacturingController::class, 'displayOutputManufacturing'])->middleware('check-read-output-manufacturing');
    //////////////////////عرض محتويات البحرات//////////////////
    Route::get('display-lake-content',[WarehouseController::class, 'displayLakeContent'])->middleware('check-read-content-lake');
    /////////////////////عرض محتويات البراد الصفري /////////////////////////////////
    Route::get('display-zero-frige-content',[WarehouseController::class, 'displayZeroFrigeContent'])->middleware('check-read-content-zero-frige');
    ////////////////////عرض محتويات مستودع الصواعق 1//////////////////////////
    Route::get('display-det-1-content',[WarehouseController::class, 'displayDetonatorFrige1Content'])->middleware('check-read-content-det1');
    ////////////////////عرض محتويات مستودع الصواعق 2//////////////////////////
    Route::get('display-det-2-content',[WarehouseController::class, 'displayDetonatorFrige2Content'])->middleware('check-read-content-det2');
    ////////////////////عرض محتويات مستودع الصواعق 3//////////////////////////
    Route::get('display-det-3-content',[WarehouseController::class, 'displayDetonatorFrige3Content'])->middleware('check-read-content-det3');
    //استعراض محتويات المخزن النهائي
    Route::get('display-store-content',[WarehouseController::class, 'displayStoreContent'])->middleware('check-read-content-store');



    ///////////////////////////// DAILY WAREHOUSE REPORTS //////////////////////////////////
    // حركة الدخل التي حصلت اليوم إلى المخزن
    Route::get('daily-input-movements',[WarehouseController::class, 'dailyInputMovements']);
    //حركة الخرج التي حصلت اليوم المخزن
    Route::get('daily-output-movements',[WarehouseController::class, 'dailyOutputMovements']);
    //حركة الإتلاف اليوم
    Route::get('get-expirations',[WarehouseController::class, 'getExpirations']);
    //الأوامر المنفذة 
    Route::get('get-done-commands',[WarehouseController::class, 'getDoneCommands']);
    //الأوامر الغير المنفذة 
    Route::get('get-non-done-commands',[WarehouseController::class, 'getNotDoneCommands']);
    // المواد التي نقصت فيها المخزون الاحتياطي اليوم
    Route::get('get-warehouse-under-stockpile',[WarehouseController::class, 'getWarehouseUnderStockpile']);

    // المواد التي خرجت اليوم الى الاتلاف و دخلت لمخزن الاتلاف
    Route::get('get-output-types-to-expiration-warehouse',[WarehouseController::class, 'getOutputTypesInsertedToExpirationWarehouse']);


    
    
    
    
});
