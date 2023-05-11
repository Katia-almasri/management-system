<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-warehouse-supervisor'] ,function(){
        
        Route::post('set-from-slaughter-to-lakes',[WarehouseController::class, 'setNewFromSlaughterToLakes']);
        
        Route::get('insert-new-element-in-warehouse',[WarehouseController::class, 'insertNewElementInWarehouse']);
        
        Route::get('display-lake-details-movement/{lakeId}',[WarehouseController::class, 'displayLakeDetailsMovement']);

        Route::get('display-zero-details-movement/{zeroId}',[WarehouseController::class, 'displayZeroDetailsMovement']);
        
        Route::post('set-from-lake-to-zero-frige',[WarehouseController::class, 'inputFromLakeToZeroFrige']);
        
        


    });



});
