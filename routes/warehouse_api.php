<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SalesPurchasingRequestController;


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
        // Route::get('display-lake-content',[WarehouseController::class, 'displayLakeContent']);

        //استعراض محتوى البراد الصفري
        // Route::get('display-zero-frige-content',[WarehouseController::class, 'displayZeroFrigeContent']);

        //استعراض محتويات الصاعقة 1
        // Route::get('display-det-1-content',[WarehouseController::class, 'displayDetonatorFrige1Content']);

        //استعراض محتويات الصاعقة 2
        // Route::get('display-det-2-content',[WarehouseController::class, 'displayDetonatorFrige2Content']);

        //استعراض محتويات الصاعقة 3
        // Route::get('display-det-3-content',[WarehouseController::class, 'displayDetonatorFrige3Content']);

        // //استعراض محتويات المخزن النهائي
        // Route::get('display-store-content',[WarehouseController::class, 'displayStoreContent']);

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
        Route::get('display-store-output-mov',[WarehouseController::class, 'displayStoreOutputMov']);
        ////////////////////استعراض كافة أسماْ المخازن ///////////////////////
        Route::get('display-warehouses-types',[WarehouseController::class, 'displayWarehousesTypes']);

        /////////////////////////////////// NOTIFICATION PART ////////////////////////////
        //استعراض إشعارات المواد المخرجة إلى الإتلاف
        Route::get('display-expiration-notification',[WarehouseController::class, 'displayExpirationNotification']);
        // استعراض جميع عمليات المواد المخرجة إلى الإتلاف
        Route::get('display-all-output-expiration',[WarehouseController::class, 'displayAlloutputExpiration']);
        // استعراض فقط إشعارات الخرج إلى مستودع الإتلاف التي لم يتم إدخالها إلى المتودع بعد
        Route::get('get-not-input-detructed-types',[WarehouseController::class, 'getNotInputDestructedTypes']);

        // استعراض الإشعارات وتبديل حالتها
        Route::get('display-all-daily-warehouse-reports-notification',[WarehouseController::class, 'displayAllDailyWarehouseReportsNtification']);


        // استعراض إشعارات التقارير اليومية للمخازن
        Route::get('display-daily-warehouse-notification-reports',[WarehouseController::class, 'displayDailyWarehouseNotificationReports']);

        // اتسعراض إشعارات أوامر الإخراج من مدير المشتريات والمبيعات
        Route::get('display-sales-command-notification',[WarehouseController::class, 'displaySalesCommandNotification']);

        //استعراض أوامر الإخراج من مدير المشتريات والمبيعات مع تغيير الحالة
        Route::get('display-sales-command-notification-switch-state',[WarehouseController::class, 'displaySalesCommandNotificationSwitchState']);

        ////////////////////////////////// END NOTIFICATION PART /////////////////////////
        //أستعراض تفاصيل مادة سوف تدخل إلى مستودع الإتلاف
        Route::get('display-output-expired-detail/{notification_id}',[WarehouseController::class, 'displayOutputExpiredDetail']);
        //ملء إدخال مادة متلفة إلى مستودع الإتلاف
        Route::post('fill-output-expiration/{notification_id}',[WarehouseController::class, 'fillInputExpiration']);

        //استعراض محتويات مستودع الإتلاف
        Route::get('display-expiration-warehouse',[WarehouseController::class, 'displayExpirationWarehouse']);

        /////////////////////////////////////// DESTRUCTION PART //////////////(إتلاف)
        //إتلاف من مستودع البحرات
        Route::post('destruct-from-lake-details/{lake_detail_id}',[WarehouseController::class, 'destructFromLakeDetails']);

        //إتلاف من مستودع البراد الصفري
        Route::post('destruct-from-zero-frige-details/{zero_frige_detail_id}',[WarehouseController::class, 'destructFromZeroFrigeDetails']);

        //إتلاف من مستودع الصاعقة 1
        Route::post('destruct-from-det1-frige-details/{det1_frige_detail_id}',[WarehouseController::class, 'destructFromDet1FrigeDetails']);

        //إتلاف من مستودع الصاعقة 2
        Route::post('destruct-from-det2-frige-details/{det2_frige_detail_id}',[WarehouseController::class, 'destructFromDet2FrigeDetails']);

        //إتلاف من مستودع الصاعقة 3
        Route::post('destruct-from-det3-frige-details/{det3_frige_detail_id}',[WarehouseController::class, 'destructFromDet3FrigeDetails']);

        //إتلاف من مستودع المخزن النهائي
        Route::post('destruct-from-store-details/{store_detail_id}',[WarehouseController::class, 'destructFromStoreDetails']);

        /////////////////////////// END DESTRUCTION PART /////////////////////////////////
        // استعراض الأوامر من مدير المشتريات
        Route::get('display-command-sales-request',[SalesPurchasingRequestController::class, 'displayCommandSalesRequest']);
        // ملء أمر الإخراج من مدير المشتريات
        Route::post('fill-sales-command/{command_sales_id}',[WarehouseController::class, 'fillCommandFromSalesManager']);
    });



});
