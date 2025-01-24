<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('WB_API_BASE_URL');
    }

    public function fetchEndpointData(string $endpoint, array $params = [])
    {
        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error fetching data from API: ' . $response->status());
    }

    public function fetchStocks(string $dateFrom, string $dateTo, int $page = 1, int $limit = 10){
        $data = $this->fetchEndpointData('stocks', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);

        $this->saveToDatabase('stocks', $data['data']);
        return $data;
    }
    public function fetchIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 10){
        $data = $this->fetchEndpointData('incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        $this->saveToDatabase('incomes', $data['data']);
    }
    public function fetchSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 10){
        $data = $this->fetchEndpointData('sales', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        $this->saveToDatabase('sales', $data['data']);
    }
    public function fetchOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500){
        $data = $this->fetchEndpointData('orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => env("API_KEY"),
            'limit' => $limit
        ]);
        $this->saveToDatabase('orders', $data['data']);
    }

    private function saveToDatabase(string $tableName, array $data)
    {
        foreach ($data as $item) {
            // Используем транзакцию для безопасного сохранения данных
            DB::transaction(function () use ($tableName, $item) {
                DB::table($tableName)->updateOrInsert(
                    ['id' => $item['id'] ?? null], // Укажите уникальное поле, если оно есть (например, 'id' или 'sale_id')
                    $item
                );
            });
        }
    }
}
