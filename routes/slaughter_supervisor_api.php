<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaughterSupervisorController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-slaughter-supervisor'] ,function(){
        Route::get('display-input-slaughters',[SlaughterSupervisorController::class, 'displayInputSlaughters']);
        Route::get('add',[SlaughterSupervisorController::class, 'addOutputSlaughters']);
        Route::post('change-state-input/{inputId}',[SlaughterSupervisorController::class, 'changeStateInput']);
        //عرض دخل الذبح في واجهة الخرج
        Route::get('display-input-total-weight',[SlaughterSupervisorController::class, 'displayInputTotalWeight']);
        Route::post('add-output/{type_id}',[SlaughterSupervisorController::class, 'addOutputSlaughters']);



    });



});
