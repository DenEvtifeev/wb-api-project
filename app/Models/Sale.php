<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model implements HasRulesInterface
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
        'is_supply',
        'is_realization',
        'promo_code_discount',
        'warehouse_name',
        'country_name',
        'oblast_okrug_name',
        'region_name',
        'income_id',
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno',
        'record_hash'
    ];

    private array $rules = [
        'g_number' => 'required|string|max:255',
        'date' => 'required|date',
        'last_change_date' => 'required|date',
        'supplier_article' => 'nullable|string|max:255',
        'tech_size' => 'nullable|string|max:255',
        'barcode' => 'nullable|integer',
        'total_price' => 'required|numeric|min:0',
        'discount_percent' => 'required|numeric|min:0|max:100',
        'is_supply' => 'required|boolean',
        'is_realization' => 'required|boolean',
        'promo_code_discount' => 'nullable|numeric|min:0',
        'warehouse_name' => 'nullable|string|max:255',
        'country_name' => 'nullable|string|max:255',
        'oblast_okrug_name' => 'nullable|string|max:255',
        'region_name' => 'nullable|string|max:255',
        'income_id' => 'nullable|integer',
        'sale_id' => 'nullable|string|max:255',
        'odid' => 'nullable|string|max:255',
        'spp' => 'nullable|numeric|min:0',
        'for_pay' => 'nullable|numeric|min:0',
        'finished_price' => 'nullable|numeric|min:0',
        'price_with_disc' => 'nullable|numeric|min:0',
        'nm_id' => 'nullable|integer',
        'subject' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'is_storno' => 'nullable|boolean',
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
