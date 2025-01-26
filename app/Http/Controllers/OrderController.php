<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Http\Controllers\Controller as BaseController;


class OrderController extends BaseController
{

    public function fetchOrders($dateFrom, $dateTo)
    {
        $apiService = new ApiService();
        $apiService->fetchOrders($dateFrom, $dateTo);
    }
}
