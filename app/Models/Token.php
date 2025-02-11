<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'token_type_id'];

    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
