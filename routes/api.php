<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::post('login',[Controller::class, 'Login'])->name('Login');
Route::group( ['middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[Controller::class, 'logout']);

    //  drop down مواد للشراء
    Route::get('get-row-materials',[Controller::class, 'getRowMaterial']);

    // drop down منتجات للبيع
    Route::get('get-products',[Controller::class, 'getProducts']);

    //drop down أنواع منافذ البيع
    Route::get('get-selling-port-types',[Controller::class, 'getSellingPortType']);
    

    


});
