<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::post('login',[Controller::class, 'Login'])->name('Login');
Route::group( ['middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[Controller::class, 'logout']);


});
