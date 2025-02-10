<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiServiceModel;

class AddApiService extends Command
{
    protected $signature = 'add:api-service {name} {base_url} {fetch_url}';
    protected $description = 'Добавить новый API сервис';

    public function handle()
    {
        $name = $this->argument('name');
        $base_url = $this->argument('base_url');
        $fetch_url = $this->argument('fetch_url');

        $apiService = ApiServiceModel::create([
            'name' => $name,
            'base_url' => $base_url,
            'fetch_url' => $fetch_url
        ]);

        $this->info("API сервис '{$apiService->name}' успешно добавлен с ID {$apiService->id}");
    }
}
