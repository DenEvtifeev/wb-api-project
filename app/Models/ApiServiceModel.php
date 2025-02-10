<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiServiceModel extends Model
{
    use HasFactory;

    protected $table = 'api_services';
    protected $fillable = ['name', 'base_url', 'fetch_url'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
