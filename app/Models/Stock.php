<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'date',
        'account_id',
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
    ];

    private array $rules = [
        'date'               => 'required|date',
        'last_change_date'   => 'required|date',
        'supplier_article'   => 'nullable|string|max:255',
        'tech_size'          => 'nullable|string|max:255',
        'barcode'            => 'nullable|integer',
        'quantity'           => 'required|integer|min:0',
        'is_supply'          => 'required|boolean',
        'is_realization'     => 'required|boolean',
        'quantity_full'      => 'required|integer|min:0',
        'warehouse_name'     => 'nullable|string|max:255',
        'in_way_to_client'   => 'required|integer|min:0',
        'in_way_from_client' => 'required|integer|min:0',
        'nm_id'              => 'nullable|integer',
        'subject'            => 'nullable|string|max:255',
        'category'           => 'nullable|string|max:255',
        'brand'              => 'nullable|string|max:255',
        'sc_code'            => 'nullable|integer',
        'price'              => 'required|numeric|min:0',
        'discount'           => 'required|numeric|min:0|max:100',
    ];

    public function getRules(): array
    {
        return $this->rules;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
