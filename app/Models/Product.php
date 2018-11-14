<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'group_date',
        'name',
        'sell_price',
        'cost_price',
        'order_format',
        'product_number',
        'product_unit',
    ];

    public function getMaxDate()
    {
        $maxDate = $this->max('group_date');

        return $maxDate ? $maxDate : date('Y-m-d');
    }

    public function getRelateByDate($date = false)
    {
        $date = $date ? $date : $this->getMaxDate();

        return $this->where('group_date', $date)->orderBy('id', 'asc')->pluck('name', 'id');
    }
}
