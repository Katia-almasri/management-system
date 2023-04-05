<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SellingPortController;
use App\Http\Controllers\ContractController;

Route::post('register',[SellingPortController::class, 'register']);
Route::post('login',[SellingPortController::class, 'Login'])->name('Login');

Route::group( ['middleware' => ['auth:selling-port-api'] ],function(){
        Route::get('display-request',[SellingPortController::class, 'displaySellingPortRequest']);
        Route::post('add-request-to-company',[SellingPortController::class, 'addRequestFromCompany']);
        //إضافة طلب عقد
        Route::post('add-request-contract',[ContractController::class, 'addRequestContract']);

});

