<?php

namespace App\Models;

use App\Interfaces\HasRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements HasRulesInterface
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
        'updated_at',
        'created_at',
    ];
    private array $rules =
        [
            'g_number'          => 'required|string|max:50', // Уникальный номер заказа
            'date'              => 'required|date',         // Дата заказа
            'last_change_date'  => 'required|date',         // Дата последнего изменения
            'supplier_article'  => 'nullable|string|max:255', // Артикул поставщика
            'tech_size'         => 'nullable|string|max:255', // Технический размер
            'barcode'           => 'nullable|integer',        // Штрих-код
            'total_price'       => 'nullable|numeric',        // Общая цена
            'discount_percent'  => 'nullable|integer|min:0|max:100', // Процент скидки
            'warehouse_name'    => 'nullable|string|max:255', // Название склада
            'oblast'            => 'nullable|string|max:255', // Область
            'income_id'         => 'nullable|integer',        // ID прихода
            'odid'              => 'nullable|string|max:255', // ID заказа
            'nm_id'             => 'required|integer',        // Номер модели
            'subject'           => 'nullable|string|max:255', // Субъект (тип товара)
            'category'          => 'nullable|string|max:255', // Категория
            'brand'             => 'nullable|string|max:255', // Бренд
            'is_cancel'         => 'nullable|boolean',        // Отменён ли заказ
            'cancel_dt'         => 'nullable|date',           // Дата отмены
        ];
    public function getRules(): array
    {
        return $this->rules;
    }


}
