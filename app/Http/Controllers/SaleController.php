<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Http\Controllers\Controller as BaseController;

class SaleController extends BaseController
{
    public function fetchSales($dateFrom, $dateTo)
    {
        $apiService = new ApiService();
        $apiService->fetchSales($dateFrom, $dateTo);
    }
}
