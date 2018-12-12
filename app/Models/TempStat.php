<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempStat extends Model
{
    protected $table = 'temp_stat';

    protected $fillable = [
        'month_profit',
        'month_price',
    ];

    public function getLastData()
    {
        return $this->orderBy('id', 'desc')->first();
    }
}
