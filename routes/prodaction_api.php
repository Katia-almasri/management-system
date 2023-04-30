<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductionController;


Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-production-manager'] ,function(){
        Route::get('display-notes',[NoteController::class, 'displayNote']);
        Route::get('display-commander',[ProductionController::class, 'displayLibraCommanderOutPut']);
        Route::post('approved-detail/{detailCommandId}',[ProductionController::class, 'approveCommanderDetail'])
        ->middleware('is-approved-material');
        Route::get('display-input',[ProductionController::class, 'displayInputProduction']);
        Route::get('command-slaughter-supervisor',[ProductionController::class, 'CommandSlaughterSupervisor']);
        Route::post('add-type-output',[ProductionController::class, 'addTypeToProductionOutPut']);
        Route::get('display-type-output',[ProductionController::class, 'displayTypeProductionOutPut']);

        Route::delete('delete-type-output/{typeId}',[ProductionController::class, 'deleteFromProdctionOutPut'])
        ->middleware('is-deleted-type');






    });



});
