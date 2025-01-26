<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Sale extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'g_number',
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
        'updated_at',
        'created_at',
    ];


    private array $rules =
        [
            'g_number' => 'required|string|max:255',
            'date' => 'required|date',
            'last_change_date' => 'nullable|date',
            'supplier_article' => 'nullable|string|max:255',
            'tech_size' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'is_supply' => 'nullable|boolean',
            'is_realization' => 'nullable|boolean',
            'promo_code_discount' => 'nullable|numeric|min:0',
            'warehouse_name' => 'nullable|string|max:255',
            'country_name' => 'nullable|string|max:255',
            'oblast_okrug_name' => 'nullable|string|max:255',
            'region_name' => 'nullable|string|max:255',
            'income_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'odid' => 'nullable|integer',
            'spp' => 'nullable|numeric|min:0',
            'for_pay' => 'nullable|numeric|min:0',
            'finished_price' => 'nullable|numeric|min:0',
            'price_with_disc' => 'nullable|numeric|min:0',
            'nm_id' => 'nullable|integer',
            'subject' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'is_storno' => 'nullable|boolean',
        ];
    public function getRules(): array
    {
        return $this->rules;
    }
}
