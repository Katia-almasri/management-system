<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DynamicPDFController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('contract',[ContractController::class, 'index']);

Route::get('dynamic_pdf',[DynamicPDFController::class, 'index'] );

Route::get('dynamic_pdf/pdf', [DynamicPDFController::class, 'pdf']);



