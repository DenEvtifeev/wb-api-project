<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class AddCompany extends Command
{
    protected $signature = 'add:company {name}';
    protected $description = 'Добавить новую компанию';

    public function handle()
    {
        $name = $this->argument('name');

        $company = Company::create(['company_name' => $name]);

        $this->info("Компания '{$company->company_name}' успешно добавлена с ID {$company->id}");
    }
}
