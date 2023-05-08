<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaughterSupervisorController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-slaughter-supervisor'] ,function(){
        Route::get('display-input-slaughters',[SlaughterSupervisorController::class, 'displayInputSlaughters']);
        Route::post('change-state-input',[SlaughterSupervisorController::class, 'changeStateInput']);
        //عرض دخل الذبح في واجهة الخرج
        Route::get('display-input-total-weight',[SlaughterSupervisorController::class, 'displayInputTotalWeight']);
        Route::post('add-output',[SlaughterSupervisorController::class, 'addOutputSlaughters']);
        // ->middleware('is-exist-type-id-input-slaughters');
        // Route::post('processing-is-done',[SlaughterSupervisorController::class, 'processing_is_done'])->middleware('is-exist-input-slaughters');
        Route::get('display-types',[SlaughterSupervisorController::class, 'displayOutputTypes']);
        Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter']);



    });



});
