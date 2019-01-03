<?php

namespace App\Services;


class OrderService
{
    public function getDayStat($orders)
    {
        $today = [
            'costPrice' => 0,
            'profit' => 0,
            'freight' => 0,
            'sellCount' => 0,
        ];

        foreach ($orders as $order)
        {
            $today['costPrice'] += $order['cost_price'];
            $today['profit'] += $order['sell_price'] - $order['cost_price'];
            $today['freight'] += $order['freight'];
            $today['sellCount'] += $order['sell_count'];
        }

        return $today;
    }

    public function getMonthStat($dayStat, $monthStat)
    {
        $month = [];
        $month['price'] = $monthStat['month_price'] + $dayStat['costPrice'];
        $month['profit'] = $monthStat['month_profit'] + $dayStat['profit'];

        return $month;
    }


}
