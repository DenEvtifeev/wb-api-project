<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;

Route::get('/', function () {});
Route::get('/incomes/{dateFrom}/{dateTo}', [IncomeController::class, 'fetchIncomes']);
Route::get('/orders/{dateFrom}/{dateTo}', [OrderController::class, 'fetchOrders']);
Route::get('/sales/{dateFrom}/{dateTo}', [SaleController::class, 'fetchSales']);
Route::get('/stocks/{dateFrom}/{dateTo}', [StockController::class, 'fetchStocks']);
