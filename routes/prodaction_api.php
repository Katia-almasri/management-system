<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SlaughterSupervisorController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\ManufacturingController;


Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-production-manager'] ,function(){

        Route::get('display-libra-output',[ProductionController::class, 'displayLibraCommanderOutPut']);
        // Route::post('approved-detail',[ProductionController::class, 'approveCommanderDetail'])
        // ->middleware('is-current-weight-and-id');
        // Route::get('display-input',[ProductionController::class, 'displayInputProduction']);
        // Route::get('command-slaughter-supervisor',[ProductionController::class, 'CommandSlaughterSupervisor'])
        // ->middleware('is-exist-input-production');
        Route::post('add-type-output',[ProductionController::class, 'addTypeToProductionOutPut']);
        Route::get('display-type-output',[ProductionController::class, 'displayTypeProductionOutPut']);

        Route::delete('delete-type-output/{typeId}',[ProductionController::class, 'deleteFromProdctionOutPut'])
        ->middleware('is-deleted-type');
        Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter']);
        // Route::get('display-output-cutting',[CuttingController::class, 'displayOutputCutting']);
        // Route::get('display-output-manufacturing',[ManufacturingController::class, 'displayOutputManufacturing']);


        Route::post('command-direct-to',[ProductionController::class, 'directTo'])->middleware('is-already-direct-to');

        //ملاحظام لمدير المشتريات والمبيعات
        Route::Post('add-note',[NoteController::class, 'AddNoteForSalesManager']);
        /////////////عرض الملاحظات///////////////////
        Route::get('display-notes',[NoteController::class, 'displayNoteProduction']);
        ///////////حذف ملاحظة/////////////////////////////
        Route::delete('delete-note-by-production/{noteId}',[NoteController::class, 'deleteNoteByProduction'])->middleware('is-note-exist');

        //////////////////// أوامر مدير الإنتاج لمشرف المحازن
        //دروب داون لمدير الإنتاج (التقطيع و التصنيع)
        Route::get('drop-down-production-manager',[ProductionController::class, 'dropDownProducationManager']);

        //دروب داون لمدير الإنتاج By section
        Route::get('drop-down-production-manager-by-section',[ProductionController::class, 'dropDownProducationManagerBySection']);


        Route::Post('add-command-to-warehouse',[ProductionController::class, 'addCommandToWarehouse']);
        // Route::get('display-warehouse',[ProductionController::class, 'displayWarehouse']);
        Route::get('display-command-warehouse',[ProductionController::class, 'displayCommandsWarehousToProduction']);



        /////////////////NOTIFICATION PART ////////////////////////
        ////////////// استعراض الإشعارات الغير مقروءة للملاحظات
        Route::get('get-unread-note-notification',[NoteController::class, 'displayNotReadNotification']);

        //////////////مع تغيير الحالة استعراض الإشعارات الغير مقروءة للملاحظات
        Route::get('get-unread-note-notification-swotch-state',[NoteController::class, 'displayNotReadNotificationSwitchState']);

        //استعراض إشعارات التقرر اليومي
        Route::get('display-daily-production-notification-report',[ProductionController::class, 'displayDailyProductionNotificationReports']);

        //استعراض إشعارات التقرر اليومي مع تغيير الحالة
        Route::get('display-daily-production-notification-report-change-state',[ProductionController::class, 'displayAllDailyProductionReportsNtification']);
        

        //////////////////////////dashboard /////////////////////
        Route::get('chart-input-production',[ProductionController::class, 'chartInputProduction']);
        Route::get('chart-output-production',[ProductionController::class, 'chartOutputSlaughter']);
        Route::get('chart-output-cutting',[ProductionController::class, 'chartOutputCutting']);
        Route::get('chart-output-manufacturing',[ProductionController::class, 'chartOutputManufacturing']);
        Route::get('perfect-production',[ProductionController::class, 'theBestOfProductProduction']);

        ////////////////////// DAILY REPORT /////////////////////
        Route::get('read-daily-prduction-report',[ProductionController::class, 'readDailyProductionReport']);
        
    });



});
