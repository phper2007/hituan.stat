<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_date',
        'name',
        'sell_price',
        'cost_price',
    ];

    public function getMaxDate()
    {
        $maxDate = $this->max('product_date');

        return $maxDate ? $maxDate : date('Y-m-d');
    }

    public function getRelateByDate($date = false)
    {
        $date = $date ? $date : $this->getMaxDate();

        return $this->where('product_date', $date)->orderBy('id', 'asc')->pluck('name', 'id');
    }
}
