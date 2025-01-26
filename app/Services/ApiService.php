<?php

namespace App\Services;

use App\Interfaces\HasRulesInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Income;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('WB_API_BASE_URL');
    }

    /**
     * @throws \Exception
     */
    private function fetchEndpointData(string $endpoint, array $params = [])
    {
        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error fetching data from API: ' . $response->status());
    }

    /**
     * @throws ValidationException
     */
    private function validateData(HasRulesInterface $dataModel, array $data): bool
    {
        $validator = Validator::make($data, $dataModel->getRules());

        return !$validator->fails();


    }

    public function fetchStocks(string $dateFrom, string $dateTo, int|array $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('stocks', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        foreach ($jsonData['data'] as $stockData) {
            $newStock = new Stock();
            if ($this->validateData($newStock, $stockData)) {
                $newStock->fill($stockData);
                $newStock->save();
            } else {
                return (new JsonResponse())->setStatusCode(404, 'Wrong data');
            }
        }
        return (new JsonResponse())->setStatusCode(200, 'Good data');

    }

    public function fetchIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        foreach ($jsonData['data'] as $incomeData) {
            $newIncome = new Income();
            if ($this->validateData($newIncome, $incomeData)) {
                $newIncome->fill($incomeData);
                $newIncome->save();
            } else {
                return (new JsonResponse())->setStatusCode(404, 'Wrong data');
            }
        }
        return (new JsonResponse())->setStatusCode(200, 'Good data');

    }

    public function fetchSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('sales', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        foreach ($jsonData['data'] as $saleData) {
            $newSale = new Sale();
            if ($this->validateData($saleData, $saleData)) {
                $newSale->fill($saleData);
                $newSale->save();
            } else {
                return (new JsonResponse())->setStatusCode(404, 'Wrong data');
            }
        }
        return (new JsonResponse())->setStatusCode(200, 'Good data');

    }

    public function fetchOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        foreach ($jsonData['data'] as $orderData) {
            $newOrder = new Order();
            if ($this->validateData($newOrder, $orderData)) {
                $newOrder->fill($orderData);
                $newOrder->save();
            } else {
                return (new JsonResponse())->setStatusCode(404, 'Wrong data');
            }
        }
        return (new JsonResponse())->setStatusCode(200, 'Good data');


    }

}
