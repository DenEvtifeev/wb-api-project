<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'token_id', 'api_service_id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiServiceModel::class);
    }
}
