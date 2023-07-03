<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SellingPortController;
use App\Http\Controllers\SalesPurchasingRequestController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TruckContoller;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ChartController;


Route::group(['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers']], function () {

    Route::group(['middleware' => 'is-mechanism-coordinator'], function () {
        ///////////////اضافة شاحنة/////////////////////
        Route::post('add-trucks', [TruckContoller::class, 'AddTruck']);
        ///////////////عرض الشاحنات///////////////////
        Route::get('display-trucks', [TruckContoller::class, 'displayTruck']);

        Route::group(['middleware' => 'is-truck-exist'], function () {
            /////////////تعديل حالة شاحنة
            Route::post('update-state/{TruckId}', [TruckContoller::class, 'UpdateTruckState']);

            //حذف شاحنة
            Route::delete('soft-delete-truck/{TruckId}', [TruckContoller::class, 'SoftDeleteTruck'])->middleware('is-truck-used');

        });
        // حالة شاحنات drop down
        Route::get('drop-down-truck-state', [TruckContoller::class, 'getTruckStates']);

        // حالة سائق drop down
        Route::get('drop-down-driver-state', [TruckContoller::class, 'getDriverStates']);

        //استرجاع شاحنة محذوفة
        Route::post('restore-truck/{TruckId}', [TruckContoller::class, 'restoreTruck'])->middleware('is-deleted-truck-exist');
        //عرض الشاحنات المحذوفة
        Route::get('display-truck-trashed', [TruckContoller::class, 'TruckTrashed']);

        Route::group(['middleware' => 'is-driver-exist'], function () {
            //حذف سائق
            Route::delete('soft-delete-driver/{DriverId}', [DriverController::class, 'SoftDeleteDriver'])->middleware('is-driver-used');

            //تعديل حالة سائق
            Route::post('update-state-driver/{DriverId}', [DriverController::class, 'UpdateDriverState']);
        });
        //استرجاع سائق محذوف
        Route::post('restore-driver/{DriverId}', [DriverController::class, 'restoreDriver'])->middleware('is-deleted-driver-exist');

        ///////////////اضافة سائق/////////////////////
        Route::post('add-driver', [DriverController::class, 'AddDriver']);
        ///////////////عرض سائق///////////////////
        Route::get('display-driver', [DriverController::class, 'displayDriver']);
        //عرض السائقين المحذوفة
        Route::get('display-driver-trashed', [DriverController::class, 'DriverTrashed']);
        //استعراض الطلبات بعد أمر مدير المشتريات والمبيعات
        Route::get('display-request', [SalesPurchasingRequestController::class, 'displaySalesPurchasingRequestFromMachenism']);


        //ادخال معلومات الرحلة
        Route::post('add-detail-trip/{requestId}', [TripController::class, 'AddDetailTrip'])->middleware('is-trip-exist');
        //عرض كل الرحلات
        Route::get('display-trips', [TripController::class, 'displayTrip']);
        Route::get('display-command', [TripController::class, 'displayCommandSalesPurchasing']);
        Route::delete('delete-trip/{TripId}', [TripController::class, 'SoftDeleteTrip']);

        //احصاءات
        //عدد الشاحنات المتاحة
        Route::get('count-avaiable-truck', [ChartController::class, 'CountAvaiableTrucks']);
        //عدد الشاحنات الكلي
        Route::get('count-trucks', [ChartController::class, 'CountTrucks']);
        //عدد السائقين الكلي
        Route::get('count-drivers', [ChartController::class, 'CountDriver']);
        //عدد السائقين المتاحين
        Route::get('count-avaiable-drivers', [ChartController::class, 'CountAvaiableDriver']);
        //شارت للرحلات
        Route::get('count-trips-chart', [ChartController::class, 'CountTrip']);

        /////////////////////// NOTIFICATION PART /////////////////////
        // استعراض إشعارات أوامر مدير المشتريات والمبيعات لمنسق حركة الآليات
        Route::get('display-command-notification', [SalesPurchasingRequestController::class, 'displyCommandNotification']);

        // مع تغيير الحالة استعراض إشعارات أوامر مدير المشتريات والمبيعات لمنسق حركة الآليات
        Route::get('display-command-notification-change-state', [SalesPurchasingRequestController::class, 'displyCommandNotificationChangeState']);


    });



});