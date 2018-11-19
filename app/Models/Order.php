<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'group_date',
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
        'freight',
        'detail_name1',
        'detail_name2',
        'detail_name3',
        'detail_name4',
        'detail_name5',
        'detail_count1',
        'detail_count2',
        'detail_count3',
        'detail_count4',
        'detail_count5',
        'express_no',
        'express_name',
        'express_signed',
        'is_msg',
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }

    public function getCheckInfoAttribute()
    {
        $string = "{$this->contact_name}。{$this->contact_phone}。{$this->province}{$this->city}{$this->district}{$this->address}。";
        $nameArr = [];
        for ($i=1; $i<=5; $i++)
        {
            $detailName = 'detail_name'.$i;
            $countName = 'detail_count'.$i;
            if($this->{$detailName})
            {
                $nameArr[] = sprintf($this->{$detailName}, $this->{$countName});
            }
        }

        $string .= implode('，', $nameArr) . "。{$this->sell_count}";
        return $string;
    }
}
