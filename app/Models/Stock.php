<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Stock extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
        'updated_at',
        'created_at',
    ];

    private array $rules =
        [
            'date' => 'required|date',
            'last_change_date' => 'nullable|date',
            'supplier_article' => 'nullable|string|max:255',
            'tech_size' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'is_supply' => 'nullable|boolean',
            'is_realization' => 'nullable|boolean',
            'quantity_full' => 'nullable|integer|min:0',
            'warehouse_name' => 'nullable|string|max:255',
            'in_way_to_client' => 'nullable|integer|min:0',
            'in_way_from_client' => 'nullable|integer|min:0',
            'nm_id' => 'nullable|integer',
            'subject' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'sc_code' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ];

    public function getRules(): array
    {
        return $this->rules;
    }
}
