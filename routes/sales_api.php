<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SellingPortController;
use App\Http\Controllers\SalesPurchasingRequestController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Controller;

Route::group( ['middleware' => ['auth:managers-api'] ],function(){

    Route::group( ['middleware' => 'is-sales-manager'] ,function(){
        //////Farm///////////
        Route::get('get-farms',[FarmController::class, 'displayFarms']);
        Route::get('get-purchase-offer',[FarmController::class, 'displayPurchaseOffers']);
        //////////////Selling Port////////////////
        Route::get('get-selling-port',[SellingPortController::class, 'displaySellingPort']);
        Route::get('get-selling-order',[SellingPortController::class, 'displaySellingOrder']);
        //////////////add request/////////////
        Route::Post('add-requset-sales-purchasing',[SalesPurchasingRequestController::class, 'AddRequsetSalesPurchasing']);
        //عرض طلبات المبيع والشراء
        Route::get('display-requset-sales-purchasing',[SalesPurchasingRequestController::class, 'displaySalesPurchasingRequest']);
        
        Route::group( ['middleware' => 'is-sales-purchase-exist'] ,function(){
            Route::get('display-detail-requset-sales-purchasing/{RequestId}',[SalesPurchasingRequestController::class, 'displayDetailsSalesPurchasingRequest']);
            /////////////أمر لمنسق حركة الاليات/////////////////////////
            Route::Post('command-for-mechanism/{RequestId}',[SalesPurchasingRequestController::class, 'commandForMechanismCoordinator']);
        });
        

        //////////////اضافة ملاحظة لمدير الانتاج//////////////////////
        Route::Post('add-note',[NoteController::class, 'AddNoteForPuductionManager']);
        /////////////عرض الملاحظات///////////////////
        Route::get('display-notes',[NoteController::class, 'displayNote']);
        ///////////حذف ملاحظة/////////////////////////////
        Route::delete('delete-note/{noteId}',[NoteController::class, 'deleteNote'])->middleware('is-note-exist');

        Route::group( ['middleware' => 'is-selling-port-exist'] ,function(){
            //حذف منفذ بيع
            Route::delete('soft-delete-selling-port/{SellingId}',[SellingPortController::class, 'SoftDeleteSellingPort']);

        });
        
        //استرجاع منفذ بيع محذوفة
        Route::post('restore-selling-port/{SellingId}',[SellingPortController::class, 'restoreSellingPort'])->middleware('is-deleted-selling-port-exist');
        //عرض منافذ البيع المحذوفة
        Route::get('display-selling-port-trashed',[SellingPortController::class, 'SellingPortTrashed']);

        Route::group( ['middleware' => 'is-farm-exist'] ,function(){
            //حذف مزرعة
            Route::delete('soft-delete-farm/{FarmId}',[FarmController::class, 'SoftDeleteFarm']);

        });

        //استرجاع مزرعة محذوفة
        Route::post('restore-farm/{FarmId}',[FarmController::class, 'restoreFarm'])->middleware('is-deleted-farm-exist');
        //عرض مزرعة المحذوفة
        Route::get('display-farm-trashed',[FarmController::class, 'FarmTrashed']);

    });



});
