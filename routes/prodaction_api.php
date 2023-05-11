<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SlaughterSupervisorController;


Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-production-manager'] ,function(){
        
        Route::get('display-commander',[ProductionController::class, 'displayLibraCommanderOutPut']);
        Route::post('approved-detail',[ProductionController::class, 'approveCommanderDetail'])
        ->middleware('is-current-weight-and-id');
        Route::get('display-input',[ProductionController::class, 'displayInputProduction']);
        Route::get('command-slaughter-supervisor',[ProductionController::class, 'CommandSlaughterSupervisor'])
        ->middleware('is-exist-input-production');
        Route::post('add-type-output',[ProductionController::class, 'addTypeToProductionOutPut']);
        Route::get('display-type-output',[ProductionController::class, 'displayTypeProductionOutPut']);

        Route::delete('delete-type-output/{typeId}',[ProductionController::class, 'deleteFromProdctionOutPut'])
        ->middleware('is-deleted-type');
        Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter']);
        Route::post('command-direct-to',[ProductionController::class, 'directTo'])->middleware('is-already-direct-to');

        //ملاحظام لمدير المشتريات والمبيعات
        Route::Post('add-note',[NoteController::class, 'AddNoteForSalesManager']);
        /////////////عرض الملاحظات///////////////////
        Route::get('display-notes',[NoteController::class, 'displayNote']);
        ///////////حذف ملاحظة/////////////////////////////
        Route::delete('delete-note/{noteId}',[NoteController::class, 'deleteNoteByProduction'])->middleware('is-note-exist');

    });



});
