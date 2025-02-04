<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FetchApiData extends Command
{
    /**
     * Название команды.
     *
     * @var string
     */
    protected $signature = 'api:fetch-data';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Fetch data from API.';

    /**
     * Экземпляр ApiService.
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
     * Создаем команду и внедряем ApiService.
     */
    public function __construct(ApiService $apiService)
    {
        parent::__construct();
        $this->apiService = $apiService;
    }

    /**
     * Выполняем логику команды.
     */
    public function handle(): void
    {
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
            $this->apiService->fetchIncomes($dateFrom, $dateTo);

            $this->info('Fetching Orders...');
            $this->apiService->fetchOrders($dateFrom, $dateTo);

            $this->info('Fetching Sales...');
            $this->apiService->fetchSales($dateFrom, $dateTo);

            $this->info('Fetching Stocks...');
            $this->apiService->fetchStocks($dateFrom, $dateTo);

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

