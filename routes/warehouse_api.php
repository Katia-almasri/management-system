<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-warehouse-supervisor'] ,function(){
        
        // إخراج من البحرات 
        Route::post('set-from-lake-to-output',[WarehouseController::class, 'inputFromLakeToOutput']);

        // إخراج من البراد الصفري 
        Route::post('set-from-zero-to-output',[WarehouseController::class, 'inputFromZeroToOutput']);

        // إخراج من الصاعق 1 
        Route::post('set-from-det-1-to-output',[WarehouseController::class, 'inputFromDet1ToOutput']);

        // إخراج من الصاعق 2 
        Route::post('set-from-det-2-to-output',[WarehouseController::class, 'inputFromDet2ToOutput']);

        // إخراج من الصاعق 3 
        Route::post('set-from-det-3-to-output',[WarehouseController::class, 'inputFromDet3ToOutput']);

        
        
        Route::group( ['middleware' => 'is-warehouse-id-exist'] ,function(){
                    // استعراض تفاصيل مادة معينة في المخزن
        Route::get('display-warehouse-detail/{warehouseId}',[WarehouseController::class, 'displayWarehouseDetail']);

        // تعديل معلومات مادة في مخزن
        Route::post('edit-warehouse-row-info/{warehouseId}',[WarehouseController::class, 'editWarehouseRowInfo']);

        });
        
        ///////////////////////display //////////////////
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
        
        Route::group( ['middleware' => 'is-command-id-exist'] ,function(){
         // ملء أمر الإنتاج من قبل مشرف المخازن  
        Route::post('fill-command-from-production-manager/{commandId}',[WarehouseController::class, 'fillCommandFromProductionManager']);

        //استعراض تفاصيل أمر معين
        Route::get('display-command/{commandId}',[WarehouseController::class, 'displayCommand']);

        });

        //استعراض الأوامر من مدير الإنتاج
        Route::get('display-commands',[WarehouseController::class, 'displayCommands']);

        //استعراض كل محتويات المخازن
         Route::get('display-warehouse-with-details',[WarehouseController::class, 'displayWarehouseContentWithDetails']);
        
        //////////////////// حركة البحرات///////////////////////
        Route::get('display-lake-input-mov',[WarehouseController::class, 'displayLakeInputMov']);
        Route::get('display-lake-output-mov',[WarehouseController::class, 'displayLakeOutMov']);
        
        
        //////////////////// حركة البراد الصفري///////////////////////
        Route::get('display-zero-input-mov',[WarehouseController::class, 'displayZeroInputMov']);
        Route::get('display-zero-output-mov',[WarehouseController::class, 'displayZeroOutMov']);
        

        //////////////////// حركة الصاعقة 1///////////////////////
        Route::get('display-det1-input-mov',[WarehouseController::class, 'displayDet1InputMov']);
        Route::get('display-det1-output-mov',[WarehouseController::class, 'displayDet1OutMov']);
        

        //////////////////// حركة الصاعقة 2///////////////////////
        Route::get('display-det2-input-mov',[WarehouseController::class, 'displayDet2InputMov']);
        Route::get('display-det2-output-mov',[WarehouseController::class, 'displayDet2OutMov']);
        //////////////////// حركة الصاعقة 3///////////////////////
        Route::get('display-det3-input-mov',[WarehouseController::class, 'displayDet3InputMov']);
        Route::get('display-det3-output-mov',[WarehouseController::class, 'displayDet3OutMov']);
        //////////////////// حركة المخزن النهائي ///////////////////////
        Route::get('display-store-input-mov',[WarehouseController::class, 'displayStoreInputMov']);

        ////////////////////استعراض كافة أسماْ المخازن ///////////////////////
        Route::get('display-warehouses-types',[WarehouseController::class, 'displayWarehousesTypes']);


    });



});
