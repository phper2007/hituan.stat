<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_date',
        'address_id',
        'product_id',
        'province',
        'city',
        'district',
        'address',
        'contact_name',
        'contact_phone',
        'product_name',
        'sell_count',
        'sell_price',
        'cost_price',
        'detail_name1',
        'detail_name2',
        'detail_name3',
    ];


    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }

    public function getCheckInfoAttribute()
    {
        $string = "{$this->contact_name}。{$this->contact_phone}。{$this->province}{$this->city}{$this->district}{$this->address}。";
        $string .= "{$this->product_name}。{$this->sell_count}";
        return $string;
    }
}
