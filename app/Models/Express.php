<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Express extends Model
{
    protected $fillable = [
        'contact_phone',
        'contact_name',
        'product_name',
        'company_code',
        'company_name',
        'express_no',
        'express_data',
        'status',
        'website_url',
        'website_md5',
        'is_msg',
        'node_time',
    ];

    public function getTransitDataAttribute()
    {
        $expressData = $this->express_data ? json_decode($this->express_data, true) : '';

        return $expressData ? $expressData['data'] : [];
    }
}
