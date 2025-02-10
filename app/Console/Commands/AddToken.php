<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;
use App\Models\TokenType;

class AddToken extends Command
{
    protected $signature = 'add:token {token} {token_type_id}';
    protected $description = 'Добавить новый токен';

    public function handle()
    {
        $token = $this->argument('token');
        $token_type_id = $this->argument('token_type_id');

        if (!TokenType::find($token_type_id)) {
            $this->error("Тип токена с ID $token_type_id не найден.");
            return;
        }

        $newToken = Token::create([
            'token' => $token,
            'token_type_id' => $token_type_id
        ]);

        $this->info("Токен успешно добавлен с ID {$newToken->id}");
    }
}
