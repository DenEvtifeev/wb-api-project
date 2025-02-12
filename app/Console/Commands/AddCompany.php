<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class AddCompany extends Command
{
    /**
     * Название команды.
     *
     * @var string
     */
    protected $signature = 'add:company {name}';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Добавить новую компанию';

    /**
     * Выполняем логику команды.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Убедитесь, что в модели Company в $fillable присутствует 'name'
        Company::create([
            'name' => $name
        ]);

        $this->info("Компания '{$name}' успешно добавлена.");
        return 0;
    }
}
