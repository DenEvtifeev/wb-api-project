<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Income extends Model implements HasRulesInterface
{
    use HasFactory;

    protected $fillable = [
        'income_id',
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
        'updated_at',
        'created_at',
    ];


    private array $rules =
        [
            'income_id' => 'required|integer',
            'number' => 'required|string|max:255',
            'date' => 'required|date',
            'last_change_date' => 'nullable|date',
            'supplier_article' => 'nullable|string|max:255',
            'tech_size' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'date_close' => 'nullable|date',
            'warehouse_name' => 'nullable|string|max:255',
            'nm_id' => 'nullable|integer',
        ];
    public function getRules(): array
    {
        return $this->rules;
    }
}
