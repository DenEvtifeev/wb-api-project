<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'g_number',
        'account_id',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'income_id',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
        'record_hash'
    ];

    private array $rules = [
        'g_number' => 'required|string|max:50',
        'date' => 'required|date',
        'last_change_date' => 'required|date',
        'supplier_article' => 'nullable|string|max:255',
        'tech_size' => 'nullable|string|max:255',
        'barcode' => 'nullable|integer',
        'total_price' => 'required|numeric|min:0',
        'discount_percent' => 'nullable|integer|min:0|max:100',
        'warehouse_name' => 'nullable|string|max:255',
        'oblast' => 'nullable|string|max:255',
        'income_id' => 'nullable|integer',
        'odid' => 'nullable|string|max:255',
        'nm_id' => 'required|integer',
        'subject' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'is_cancel' => 'nullable|boolean',
        'cancel_dt' => 'nullable|date',
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
