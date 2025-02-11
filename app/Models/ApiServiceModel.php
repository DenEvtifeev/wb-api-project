<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiServiceModel extends Model
{
    use HasFactory;

    protected $table = 'api_services';
    protected $fillable = ['name', 'base_url', 'fetch_url'];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
