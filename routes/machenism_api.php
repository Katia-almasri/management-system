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

Route::group( ['middleware' => ['auth:managers-api'] ],function(){

    Route::group( ['middleware' => 'is-mechanism-coordinator'] ,function(){
        ///////////////اضافة شاحنة/////////////////////
        Route::post('add-trucks',[TruckContoller::class, 'AddTruck']);
        ///////////////عرض الشاحنات///////////////////
        Route::get('display-trucks',[TruckContoller::class, 'displayTruck']);

        Route::group( ['middleware' => 'is-truck-exist'] ,function(){
            /////////////تعديل حالة شاحنة
            Route::post('update-state/{TruckId}',[TruckContoller::class, 'UpdateTruckState']);
            //حذف شاحنة
            Route::delete('soft-delete-truck/{TruckId}',[TruckContoller::class, 'SoftDeleteTruck']);  

        });
        
        //استرجاع شاحنة محذوفة
        Route::post('restore-truck/{TruckId}',[TruckContoller::class, 'restoreTruck'])->middleware('is-deleted-truck-exist');
        //عرض الشاحنات المحذوفة
        Route::get('display-truck-trashed',[TruckContoller::class, 'TruckTrashed']);

        Route::group( ['middleware' => 'is-driver-exist'] ,function(){
            //حذف سائق
            Route::delete('soft-delete-driver/{DriverId}',[DriverController::class, 'SoftDeleteDriver']);
            //تعديل حالة سائق
            Route::post('update-state-driver/{DriverId}',[DriverController::class, 'UpdateDriverState']);


        });
       
        ///////////////اضافة سائق/////////////////////
        Route::post('add-driver',[DriverController::class, 'AddDriver']);
        ///////////////عرض سائق///////////////////
        Route::get('display-driver',[DriverController::class, 'displayDriver']);
        //استرجاع سائق محذوف
        Route::post('restore-driver/{driverId}',[DriverController::class, 'restoreDriver'])->middleware('is-deleted-driver-exist');
        //عرض السائقين المحذوفة
        Route::get('display-driver-trashed',[DriverController::class, 'DriverTrashed']);


        //استعراض الطلبات بعد أمر مدير المشتريات والمبيعات
        Route::get('display-request',[SalesPurchasingRequestController::class, 'displaySalesPurchasingRequestFromMachenism']);


        //ادخال معلومات الرحلة
        Route::post('add-detail-trip/{requestId}',[TripController::class, 'AddDetailTrip'])->middleware('is-trip-exist');
        //عرض كل الرحلات
        Route::get('display-trips',[TripController::class, 'displayTrip']);

    });



});
