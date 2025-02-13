<?php

namespace App\Services;

use App\Interfaces\HasRulesInterface;
use App\Models\Account;
use App\Strategies\GetParamKeyAuthStrategy;
use Illuminate\Http\JsonResponse;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Income;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
            'key' => new GetParamKeyAuthStrategy(),
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
                sleep(5);
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

    /**
     * @throws Exception
     */
    public function fetchStocks(string $dateFrom, string $dateTo, int|array $page = 1, int $limit = 500): void
    {
        $today = Carbon::today();
        // Если переданная дата меньше или равна "вчера" по серверному времени, устанавливаем dateFrom равной сегодняшней дате
        if (Carbon::parse($dateFrom)->lte(Carbon::yesterday())) {
            $dateFrom = $today->toDateString();
        }

        $this->fetchPaginatedData(Stock::class,'stocks', $dateFrom, $dateTo, $limit);
    }

    /**
     * @throws Exception
     */
    public function fetchIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): void
    {
        $this->fetchPaginatedData(Income::class, 'incomes', $dateFrom, $dateTo, $limit);
    }

    /**
     * @throws Exception
     */
    public function fetchSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): void
    {
        $this->fetchPaginatedData(Sale::class,'sales', $dateFrom, $dateTo, $limit);
    }


    /**
     * @throws Exception
     */
    public function fetchOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): void
    {
        $this->fetchPaginatedData(Order::class,'orders', $dateFrom, $dateTo, $limit);
    }

    private function saveData(string $modelClass, array $data, int $accountId): void
    {
        foreach ($data as $item) {
            // Если элемент пустой или не является массивом — пропускаем
            if (!is_array($item) || empty($item)) {
                Log::warning('Empty or invalid record encountered', $item);
                continue;
            }

            // Добавляем account_id в данные (если его еще нет)
            $item['account_id'] = $accountId;
            // Сортируем массив для стабильности хэша
            ksort($item);
            // Объединяем все значения через разделитель и вычисляем MD5-хэш
            $dataForHash = implode('|', $item);
            $item['record_hash'] = md5($dataForHash);

            $newModel = new $modelClass();

            // Проверяем валидность данных (хэш уже включен)
            if (!$this->validateData($newModel, $item)) {
                Log::warning('Wrong data: ', $item);
                continue;
            }

            $newModel->fill($item);
            $newModel->save();
        }
        Log::info('Data save process completed.');
    }

    /**
     * @throws Exception
     */
    private function fetchPaginatedData(string $modelClass, string $endpoint, string $dateFrom, string $dateTo, int $limit = 500): void
    {
        $page = 1;
        do {
            DB::beginTransaction();
            try {
                $jsonData = $this->fetchEndpointData($endpoint, [
                    'dateFrom' => $dateFrom,
                    'dateTo'   => $dateTo,
                    'page'     => $page,
                    'key'      => $this->token,
                    'limit'    => $limit,
                ]);
                $records = $jsonData['data'] ?? [];
                $this->saveData($modelClass, $records, $this->accId);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                // Можно логировать ошибку, но продолжать обработку следующих страниц
                Log::error("Error on page {$page}: " . $e->getMessage());
            }
            $page++;
        } while (count($records) === $limit);
    }




}
