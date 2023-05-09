<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SellingPortController;
use App\Http\Controllers\SalesPurchasingRequestController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ContractController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-sales-manager'] ,function(){
        //////Farm///////////
        Route::get('get-farms',[FarmController::class, 'displayFarms']);
        Route::get('get-purchase-offer',[FarmController::class, 'displayPurchaseOffers']);
        //////////////Selling Port////////////////
        Route::get('get-selling-port',[SellingPortController::class, 'displaySellingPort']);
        Route::get('get-selling-order',[SellingPortController::class, 'displaySellingOrder']);
        /////////////// استعراض جميع الطلبات المنشأة من قبل مدير المشتريات ////////////////////
        Route::get('get-selling-purchasing-requests',[SalesPurchasingRequestController::class, 'getAllSalesPurchasingRequests']);
        
        //////////////add request/////////////
        Route::Post('add-requset-sales-purchasing',[SalesPurchasingRequestController::class, 'AddRequsetSalesPurchasing']);
        /////////////أمر لمنسق حركة الاليات/////////////////////////
        Route::Post('command-for-mechanism/{RequestId}',[SalesPurchasingRequestController::class, 'commandForMechanismCoordinator'])
        ->middleware('is-request-accept');


        //////////////اضافة ملاحظة لمدير الانتاج//////////////////////
        Route::Post('add-note',[NoteController::class, 'AddNoteForPuductionManager']);
        /////////////عرض الملاحظات///////////////////
        Route::get('display-notes',[NoteController::class, 'displayNote']);
        ///////////حذف ملاحظة/////////////////////////////
        Route::delete('delete-note/{noteId}',[NoteController::class, 'deleteNoteBySales'])->middleware('is-note-exist');

        Route::group( ['middleware' => 'is-selling-port-exist'] ,function(){
            //حذف منفذ بيع
            Route::delete('soft-delete-selling-port/{sellingPortId}',[SellingPortController::class, 'SoftDeleteSellingPort']);
            //تأكيد طلب تسجيل حساب منفذ بيع
            Route::post('confirm-request-register/{sellingPortId}',[SellingPortController::class, 'commandAcceptForSellingPort']);
        });
        
        Route::get('display-non-accept',[SalesPurchasingRequestController::class, 'displayNonAcceptByCEO']);

        Route::get('display-accept',[SalesPurchasingRequestController::class, 'displayAcceptByCEO']);
        
        //استرجاع منفذ بيع محذوفة
        Route::post('restore-selling-port/{SellingId}',[SellingPortController::class, 'restoreSellingPort'])->middleware('is-deleted-selling-port-exist');
        //عرض منافذ البيع المحذوفة
        Route::get('display-selling-port-trashed',[SellingPortController::class, 'SellingPortTrashed']);
        //عرض طلبات تسجيل منفذ بيع
        Route::get('display-request-selling-port',[SellingPortController::class, 'displaySellingPortRegisterRequest']);
        Route::group( ['middleware' => 'is-selling-port-order'] ,function(){
            //تأكيد طلب طلبية شراء من قبل مدير المشتريات
            Route::post('confirm-request-order/{SellingPortOrderId}',[SellingPortController::class, 'commandAcceptForSellingPortOrder']);
            //رفض طلب طلبية منفذ بيع
            Route::post('refuse-request-order/{SellingPortOrderId}',[SellingPortController::class, 'refuseOrderDetail']);

        });


        ///////////******************////////////*********** */ */
        Route::post('aa/{ContractId}',[ContractController::class, 'addDetailToContract']);

        Route::group( ['middleware' => 'is-farm-exist'] ,function(){
            //حذف مزرعة
            Route::delete('soft-delete-farm/{FarmId}',[FarmController::class, 'SoftDeleteFarm']);
            // تأكيد حساب مزرعة
            Route::post('confirm-request-farm-register/{FarmId}',[FarmController::class, 'commandAcceptForFarm']);

        });
        //استرجاع مزرعة محذوفة
        Route::post('restore-farm/{FarmId}',[FarmController::class, 'restoreFarm'])->middleware('is-deleted-farm-exist');

        //عرض مزرعة المحذوفة
        Route::get('display-farm-trashed',[FarmController::class, 'displayFarmTrashed']);
        //عرض طلبات تسجيل حساب مزرعة
        Route::get('display-request-farms',[FarmController::class, 'displayFarmRegisterRequest']);


        Route::get('display-contracts',[ContractController::class, 'getContracts']);
        Route::get('display-contract-request-detail/{contractId}',[ContractController::class, 'getContractRequestDetail']);
        //تأكيد طلب من العروض
        Route::post('confirm_offer/{offerId}',[SalesPurchasingRequestController::class, 'requestFromOffer'])->middleware('check-offer-exist');

        
        //إشعارات طلبات تسجيل المزارع و عدد الإشعارات
        Route::get('get-register-farm-request',[SalesPurchasingRequestController::class, 'getResgisterFarmRequestsNotifs']);
        // إشعارات طلبات تسجيل منافذ البيع و عدد الإشعارات
        Route::get('get-register-selling-port-request',[SalesPurchasingRequestController::class, 'getResgisterSellingPortRequestsNotifs']);
        // إشعارات عروض المزارع و عدد الإشعارات
        Route::get('get-add-offers-notifs',[SalesPurchasingRequestController::class, 'getAddOffersNotifs']);
        
        // إشعارات طلبات منافذ البيع و عدد الإشعارات
        Route::get('get-request-to-company-notifs',[SalesPurchasingRequestController::class, 'getRequestToCompanyNotifs']);
        //عدد الشحنات الواصلة والتي تم وزنها
        Route::get('get-poultry-reciept-count-notifs',[SalesPurchasingRequestController::class, 'countPoultryRecieptDetectionsNotifs']);
        

        Route::post('confirm_offer/{offer_id}',[SalesPurchasingRequestController::class, 'requestFromOffer']);
        //تقارير لطلبات الشراء
        Route::get('daily-report',[SalesPurchasingRequestController::class, 'DailyReportSalesRequests']);
        Route::get('monthly-report',[SalesPurchasingRequestController::class, 'MonthlyReportSalesRequests']);
        Route::get('yearly-report',[SalesPurchasingRequestController::class, 'yearlyReportSalesRequests']);

        //تقارير للعروض

        Route::get('daily-offer-report',[SalesPurchasingRequestController::class, 'DailyReportoffer']);
        Route::get('monthly-offer-report',[SalesPurchasingRequestController::class, 'MonthlyReportOffer']);
        Route::get('yearly-offer-report',[SalesPurchasingRequestController::class, 'yearlyReportOffer']);


    });



});
