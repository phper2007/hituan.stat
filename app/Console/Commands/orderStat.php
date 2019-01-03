<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Models\TempStat;
use App\Services\OrderService;
use Illuminate\Console\Command;

class orderStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderStat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $statModel = new TempStat();
        $productModel = (new Product());


        $lastProduct = $productModel->orderBy('id', 'desc')->first();

        $lastStat = $statModel->orderBy('id', 'desc')->first();

        if($lastStat['created_at'] > $lastProduct['created_at'])
        {
            echo 'no new product!';
            return;
        }

        $orderService = new OrderService();

        $maxDate = $productModel->getMaxDate();

        $productList = $productModel->where('group_date', $maxDate)->get()->keyBy('id')->toArray();

        $monthStat = $statModel->getLastData();

        $orders = (new Order())
            ->where('group_date', $maxDate)
            ->orderBy('id', 'desc')->get();

        $today = $orderService->getDayStat($orders);

        $month = $orderService->getMonthStat($today, $monthStat);

        $data = [
            'month_price' => $month['price'],
            'month_profit' => $month['profit'],
        ];

        $statModel->create($data);

        echo 'ok';
    }
}
