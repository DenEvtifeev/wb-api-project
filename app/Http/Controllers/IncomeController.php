<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Http\Controllers\Controller as BaseController;
class IncomeController extends BaseController
{
    public function fetchIncomes($dateFrom, $dateTo)
    {
        $apiService = new ApiService();
        $apiService->fetchIncomes($dateFrom, $dateTo);

    }
}
