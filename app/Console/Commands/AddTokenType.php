<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TokenType;

class AddTokenType extends Command
{
    protected $signature = 'add:token-type {name}';
    protected $description = 'Добавить новый тип токена';

    public function handle()
    {
        $name = $this->argument('name');

        $tokenType = TokenType::create(['name' => $name]);

        $this->info("Тип токена '{$tokenType->name}' успешно добавлен с ID {$tokenType->id}");
    }
}
