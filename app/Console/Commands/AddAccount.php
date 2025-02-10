<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\Company;
use App\Models\Token;
use App\Models\ApiServiceModel;

class AddAccount extends Command
{
    protected $signature = 'add:account {name} {company_id?} {token_id?} {api_service_id?}';
    protected $description = 'Добавить новый аккаунт';

    public function handle()
    {
        $name = $this->argument('name');
        $company_id = $this->argument('company_id') ?? null;
        $token_id = $this->argument('token_id') ?? null;
        $api_service_id = $this->argument('api_service_id') ?? null;

        if ($company_id && !Company::find($company_id)) {
            $this->error("Компания с ID $company_id не найдена.");
            return;
        }

        if ($token_id && !Token::find($token_id)) {
            $this->error("Токен с ID $token_id не найден.");
            return;
        }

        if ($api_service_id && !ApiServiceModel::find($api_service_id)) {
            $this->error("API сервис с ID $api_service_id не найден.");
            return;
        }

        $account = Account::create([
            'name' => $name,
            'company_id' => $company_id,
            'token_id' => $token_id,
            'api_service_id' => $api_service_id
        ]);

        $this->info("Аккаунт '{$account->name}' успешно добавлен с ID {$account->id}");
    }
}
