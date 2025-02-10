<?php

namespace App\Interfaces;

use Illuminate\Http\Client\PendingRequest;

interface FetchAuthStrategyInterface
{
    public function getHttpWithAuth(string $key): PendingRequest;

}
