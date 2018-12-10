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

    public function getProductList($where)
    {
        return $this->where($where)->orderBy('id', 'asc')->get()->toArray();
    }

    public function getRelateByDate($date = false)
    {
        $date = $date ? $date : $this->getMaxDate();

        return $this->where('group_date', $date)->orderBy('id', 'asc')->pluck('name', 'id');
    }

    public function getOrderFormatByDate($date = false)
    {
        $date = $date ? $date : $this->getMaxDate();

		$list = $this->where('group_date', $date)->orderBy('id', 'asc')->get();

		$list = collect($list)->keyBy('id')->map(function ($item){
			return sprintf($item->order_format, $item->product_number);
		})->toArray();


        return $list;
    }
}
