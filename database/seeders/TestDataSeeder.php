<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ Создаём тип токена
        $tokenTypeId = DB::table('token_types')->insertGetId([
            'name' => 'key',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2️⃣ Создаём API-сервис
        $apiServiceId = DB::table('api_services')->insertGetId([
            'name' => 'Test API',
            'base_url' => "http://89.108.115.241:6969/api",
            'fetch_url' => '/incomes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        // 3️⃣ Создаём токен для аккаунта
        $tokenId = DB::table('tokens')->insertGetId([
            'token' => 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie',
            'token_type_id' => $tokenTypeId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $companyId = DB::table('companies')->insertGetId([
            'name' => 'Test Company',
        ]);

        // 4️⃣ Создаём аккаунт
        $accountId = DB::table('accounts')->insertGetId([
            'name' => 'Test Account',
            'api_service_id' => $apiServiceId,
            'token_id' => $tokenId,
            'company_id' => $companyId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
