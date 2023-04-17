<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;


Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-production-manager'] ,function(){
        Route::get('display-notes',[NoteController::class, 'displayNote']);



    });



});
