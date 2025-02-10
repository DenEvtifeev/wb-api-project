<?php

namespace App\Strategies;

use App\Interfaces\FetchAuthStrategyInterface;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\PendingRequest;

class GetParamKeyAuthStrategy implements FetchAuthStrategyInterface
{

    public function getHttpWithAuth(string $key): PendingRequest
    {
        return Http::withQueryParameters(['key' => $key]);
    }
}
