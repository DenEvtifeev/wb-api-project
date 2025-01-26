<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Http\Controllers\Controller as BaseController;

class StockController extends BaseController
{
    public function fetchStocks($dateFrom, $dateTo)
    {
        $apiService = new ApiService();
        $apiService->fetchStocks($dateFrom, $dateTo);
    }
}
