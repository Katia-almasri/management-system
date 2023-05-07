<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::post('login',[Controller::class, 'Login'])->name('Login');
Route::get('get',[Controller::class, 'get']);

Route::group( ['middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[Controller::class, 'logout']);

    //  drop down مواد للشراء
    Route::get('get-row-materials',[Controller::class, 'getRowMaterial']);

    // drop down منتجات للبيع
    Route::get('get-products',[Controller::class, 'getProducts']);

    //drop down أنواع منافذ البيع
    Route::get('get-selling-port-types',[Controller::class, 'getSellingPortType']);
    
    //استعراض وزن الشحنة بعد الوصول لكشف معين
    Route::get('get-weight-after-arrivel-detection/{recieptId}', [Controller::class, 'getWeightAfterArrival'])->middleware(['is-user-has-permission-to-read-poultry-detection','check-reciept-id', 'check-reciept-weighted']);


});
