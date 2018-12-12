<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Order;
use App\Models\TempStat;

class OrdersController extends Controller
{

    protected $productRelate = [];
    protected $productList = [];

    public function __construct()
    {
        $productModel = (new Product());

        $this->productRelate = $productModel->getRelateByDate();
        $this->productList = $productModel->getProductList(['group_date' => $productModel->getMaxDate()]);
    }

    public function index(Request $request)
    {
        $keywords = $request->input('keywords', '');

        $query = (new Order())->orderBy('id', 'desc');
        if($keywords)
        {
            $query->where(function ($query) use ($keywords){
                $query->where('contact_name', 'like', "%{$keywords}%")
                    ->orWhere('contact_phone', 'like', "%{$keywords}%")
                    ->orWhere('product_name', 'like', "%{$keywords}%");
            });
        }

        $orders = $query->get();
        return view('orders.index', [
            'orders' => $orders,
            'keywords' => $keywords,
        ]);
    }

    public function stat(Request $request)
    {
        $tempStatModel = new TempStat();
        if($request->isMethod('post'))
        {
            $data = $request->only(['month_price', 'month_profit']);
            $tempStatModel->create($data);

            return redirect()->route('orders.index');
        }

        return view('orders.stat', ['monthStat' => $tempStatModel->getLastData()]);
    }

    public function bill()
    {
        $productModel = (new Product());
        $maxDate = $productModel->getMaxDate();

        $productList = $productModel->where('group_date', $maxDate)->get()->keyBy('id')->toArray();

        $monthStat = (new TempStat())->getLastData();

        $orders = (new Order())
            ->where('group_date', $maxDate)
            ->orderBy('id', 'desc')->get();

        $today = [
            'costPrice' => 0,
            'profit' => 0,
            'freight' => 0,
            'sellCount' => 0,
        ];

        $month = [];

        foreach ($orders as $order)
        {
            $today['costPrice'] += $order['cost_price'];
            $today['profit'] += $order['sell_price'] - $order['cost_price'];
            $today['freight'] += $order['freight'];
            $today['sellCount'] += $order['sell_count'];
        }

        $month['price'] = $monthStat['month_price'] + $today['costPrice'];
        $month['profit'] = $monthStat['month_profit'] + $today['profit'];

        $orderGroup = $orders->groupBy('product_id');

        $productSum = [];
        foreach($orderGroup as $productId => $groupOrder)
        {
            $sellCount = $groupOrder->sum('sell_count');
            $productNumber = $productList[$productId]['product_number'];
            $productSum[$productId] = $sellCount * $productNumber;
        }

        return view('orders.bill', [
            'maxDate' => $maxDate,
            'orderGroup' => $orderGroup,
            'today' => $today,
            'month' => $month,
            'monthStat' => $monthStat,
            'productSum' => $productSum,
            'productRelate' => $productModel->getOrderFormatByDate($maxDate),
        ]);
    }

    public function create(UserAddress $user_address)
    {
        $order = new Order();
        $order->sell_count = 1;

        return view('orders.create_and_edit', [
            'address' => $user_address,
            'productRelate' => $this->productRelate,
            'productList' => $this->productList,
            'order' => $order,
            'orderCountDict' => config('project.orderCountDict'),
            'orderDetailCountDict' => config('project.orderDetailCountDict'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'address_id',
            'product_id',
            'sell_count',
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
        ]);

        //完善信息
        $userAddress = (new UserAddress())->find($data['address_id']);
        $product = (new Product())->find($data['product_id']);

        $data['group_date'] = $product['group_date'];
        $data['product_name'] = $product['name'];
        $data['sell_price'] = $product['sell_price']*$data['sell_count'];
        $data['cost_price'] = $product['cost_price']*$data['sell_count'];
        $data['province'] = $userAddress['province'];
        $data['city'] = $userAddress['city'];
        $data['district'] = $userAddress['district'];
        $data['address'] = $userAddress['address'];
        $data['contact_name'] = $userAddress['contact_name'];
        $data['contact_phone'] = $userAddress['contact_phone'];

        if(empty($data['detail_name1']))
        {
            $data['detail_name1'] = $product['order_format'];
        }
        $data['detail_count1'] = $data['detail_count1']>0 ? $data['detail_count1'] : $product['product_number']*$data['sell_count'];

        $order = (new Order())->create($data);

        return redirect()->route('orders.check', ['order' => $order->id]);
    }

    public function check(Order $order)
    {
        return view('orders.check', ['order' => $order]);
    }

    public function edit(UserAddress $user_address)
    {
        return view('user_addresses.create_and_edit', ['address' => $user_address]);
    }

    public function update(UserAddress $user_address, Request $request)
    {
        $user_address->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        // 把之前的 redirect 改成返回空数组
        return [];
    }
}
