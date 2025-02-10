<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;

class FetchApiData extends Command
{
    /**
     * Название команды.
     *
     * @var string
     */
    protected $signature = 'api:fetch-data {account_id}';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Fetch data from API.';

    /**
     * Экземпляр ApiServiceModel.
     *
     * @var ApiService
     */
    protected ApiService $apiService;

    /**
     * Путь к файлу состояния.
     *
     * @var string
     */
    private string $stateFile = 'fetch_data_state.json';

    /**
     * Выполняем логику команды.
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $accountId = (int) $this->argument('account_id');

        // Создаём экземпляр `ApiService` вручную, передавая `account_id`
        $apiService = app()->make(ApiService::class, ['accountId' => $accountId]);

        // Проверяем, был ли выполнен первый запуск
        $firstRun = !$this->isFirstRunCompleted();

        // Устанавливаем даты для фетчинга
        if ($firstRun) {
            // Первый запуск: период за прошлый год
            $dateFrom = Carbon::now()->subYear()->toDateString(); // Год назад
            $dateTo = Carbon::now()->toDateString(); // Сегодня
            $this->info("First run: Fetching data from $dateFrom to $dateTo.");
        } else {
            // Последующие запуски: период за текущий день и следующий
            $dateFrom = Carbon::now()->toDateString(); // Сегодня
            $dateTo = Carbon::now()->addDay()->toDateString(); // Завтра
            $this->info("Fetching data for $dateFrom to $dateTo.");
        }

        // Выполняем фетчинг данных
        try {
            $this->info('Fetching Incomes...');
            $apiService->fetchIncomes($dateFrom, $dateTo);

            $this->info('Fetching Orders...');
            $apiService->fetchOrders($dateFrom, $dateTo);

            $this->info('Fetching Sales...');
            $apiService->fetchSales($dateFrom, $dateTo);

            $this->info('Fetching Stocks...');
            $apiService->fetchStocks($dateFrom, $dateTo);

            $this->info('API data fetch completed successfully.');

            // Если это первый запуск, отмечаем его как завершённый
            if ($firstRun) {
                $this->markFirstRunCompleted();
            }
        } catch (\Exception $e) {
            $this->error('Error during API data fetch: ' . $e->getMessage());
        }
    }

    /**
     * Проверяет, был ли выполнен первый запуск.
     *
     * @return bool
     */
    private function isFirstRunCompleted(): bool
    {
        return Storage::exists($this->stateFile);
    }

    /**
     * Отмечает первый запуск как завершённый.
     */
    private function markFirstRunCompleted(): void
    {
        Storage::put($this->stateFile, json_encode(['firstRunCompleted' => true]));
    }
}

