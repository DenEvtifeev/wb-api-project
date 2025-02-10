<?php

namespace App\Services;

use App\Interfaces\HasRulesInterface;
use App\Models\Account;
use App\Strategies\GetParamKeyAuthStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Income;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class ApiService
{
    private string $baseUrl;
    private string $fetchUrl;
    private string $token;
    private int $accId;
    private string $tokenType;

    public function __construct(int $accountId)
    {
        $account = Account::with(['apiService', 'token.tokenType'])->findOrFail($accountId);

        $this->baseUrl = $account->apiService->base_url;
        $this->fetchUrl = $account->apiService->fetch_url;
        $this->token = $account->token->token;
        $this->accId = $accountId;
        $this->tokenType = $account->token->tokenType->name;
    }

    /**
     * @throws Exception
     */

    // по-хорошему эти сервисы сделать через стратегии или фабрики, так как не все апи работают только с GET параметрами или сделать отдельную стратегию или фабрику условно DataStrategy и там собирать реквест
    private function fetchEndpointData(string $endpoint, $params = [])
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($this->fetchUrl, '/') . '/' . $endpoint;
        // Формируем строку запроса для отладки
        $fullUrl = $url . '?' . http_build_query($params);
        $authStrategy = match ($this->tokenType) {
            'GetParamKey' => new GetParamKeyAuthStrategy(),
            default => null
        };

        // мне нравится такой вариант, но он очень зависит от строки и можно очень легко допустить ошибку

//        $authStrategyName = 'App\Strategies\\'.$this->tokenType.'AuthStrategy';
//        $authStrategy = new $authStrategyName();

        if (!$authStrategy) {
            throw new \Exception("Failed to fetch data");
        }
        $httpRequest = $authStrategy->getHttpWithAuth($this->token);
        dump($fullUrl);
        try {
            $response = $httpRequest->retry(5, 2000, function ($exception, $request) {
                return $exception->getCode() === 429; // Повторяем только при ошибке 429
            })->get($fullUrl, $params);
            if (!$response->successful()) {
                throw new \Exception("Failed to fetch data: " . $response->status() . ". URL: {$url}");
            }

            return $response->json();
        } catch (Exception $e) {
            echo "Error fetching data: " . $e->getMessage() . PHP_EOL;
            throw $e;
        }
    }

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
            'key' => $this->token,
            'limit' => $limit
        ]);
        $this->saveData(Stock::class, $jsonData, $this->accId);

    }

    public function fetchIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => $this->token,
            'limit' => $limit
        ]);
        $this->saveData(Income::class, $jsonData, $this->accId);

    }

    /**
     * @throws Exception
     */
    public function fetchSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('sales', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => $this->token,
            'limit' => $limit
        ]);
        $this->saveData(Sale::class, $jsonData, $this->accId);

    }


    public function fetchOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500)
    {
        $jsonData = $this->fetchEndpointData('orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => $this->token,
            'limit' => $limit
        ]);
        $this->saveData(Order::class, $jsonData, $this->accId);
    }

    private function saveData(string $modelClass, array $data, int $accountId): void
    {
        foreach ($data['data'] as $item) {
            $newModel = new $modelClass();

            $existingRecord = app($modelClass)::where('account_id', $accountId)
                ->where('date', $item['date'])
                ->first();
            if ($this->validateData($newModel, $item) && !$existingRecord) {
                $item['account_id'] = $accountId;
                $newModel->fill($item);
                $newModel->save();
            } else {
                Log::warning('Wrong data or record exists', $item);
            }
        }
        (new JsonResponse())->setStatusCode(200, 'Good data');
    }

}
