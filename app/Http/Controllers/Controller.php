<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\ApiService;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function fetchData(){
        $apiService = new ApiService();

        $apiService->fetchStocks('2025-01-24', '2025-01-26');
        $apiService->fetchSales('2025-01-23', '2025-01-24');
        $apiService->fetchIncomes('2025-01-23', '2025-01-24');
        $apiService->fetchOrders('2025-01-23', '2025-01-24');
    }
}
