<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\ContractController;

Route::post('register-farm',[FarmController::class, 'registerFarm']);
Route::post('login-farm',[FarmController::class, 'loginFarm']);

Route::group( ['middleware' => ['auth:farms-api', 'check-scope-farms', 'scopes:farms'] ],function(){
    Route::post('add-offer',[FarmController::class, 'addOffer']);

    Route::get('display-my-offer',[FarmController::class, 'displayMyOffers']);
    Route::delete('delete-offer/{offerId}',[FarmController::class, 'deleteOffer'])
    ->middleware('is-deleted-offer');



});

