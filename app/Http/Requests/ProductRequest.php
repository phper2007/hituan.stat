<?php

namespace App\Http\Requests;

class ProductRequest extends Request
{
    public function rules()
    {
        /*$rules = [
            'product_date'          => 'required',
            'name'      => 'required',
            'sell_price'  => 'required',
            'cost_price' => 'required',
        ];*/

        return [];
    }

    public function attributes()
    {
        return [
            'province'      => '日期',
            'name'          => '产品名称',
            'sell_price'      => '销售价',
            'cost_price'       => '成本价',
        ];
    }
}