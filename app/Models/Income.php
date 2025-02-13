<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'account_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
        'record_hash'
    ];

    private array $rules = [
        'income_id' => 'required|integer',
        'number' => 'nullable|string|max:255',
        'date' => 'required|date',
        'last_change_date' => 'required|date',
        'supplier_article' => 'nullable|string|max:255',
        'tech_size' => 'nullable|string|max:255',
        'barcode' => 'nullable|integer',
        'quantity' => 'required|integer|min:0',
        'total_price' => 'required|numeric|min:0',
        'date_close' => 'nullable|date',
        'warehouse_name' => 'nullable|string|max:255',
        'nm_id' => 'nullable|integer',
        'record_hash'=> 'required|string|size:32|regex:/^[a-f0-9]{32}$/i'
    ];

    public function getRules(): array
    {
        return $this->rules;
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
