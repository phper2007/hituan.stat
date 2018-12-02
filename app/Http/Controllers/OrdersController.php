<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Order;

class OrdersController extends Controller
{

    protected $productRelate = [];

    public function __construct()
    {
        $productModel = (new Product());

        $this->productRelate = $productModel->getRelateByDate();
    }

    public function index(Request $request)
    {
        $orders = (new Order())->orderBy('id', 'desc')->get();
        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function bill()
    {
        $productModel = (new Product());
        $maxDate = $productModel->getMaxDate();

        $productList = $productModel->where('group_date', $maxDate)->get()->keyBy('id')->toArray();

        $orders = (new Order())
            ->where('group_date', $maxDate)
            ->orderBy('id', 'desc')->get();

        $today = [
            'sellPrice' => 0,
            'profit' => 0,
            'freight' => 0,
            'sellCount' => 0,
        ];

        foreach ($orders as $order)
        {
            $today['sellPrice'] += $order['sell_price'] + $order['freight'];
            $today['profit'] += $order['sell_price'] - $order['cost_price'];
            $today['freight'] += $order['freight'];
            $today['sellCount'] += $order['sell_count'];
        }

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
            'order' => $order,
            'orderCountDict' => config('project.orderCountDict'),
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
            $data['detail_count1'] = $data['detail_count1'] ? $data['detail_count1'] : $product['product_number']*$data['sell_count'];
            $data['detail_name1'] = $product['order_format'];
        }

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

    public function destroy(UserAddress $user_address)
    {
        $user_address->delete();
        // 把之前的 redirect 改成返回空数组
        return [];
    }
}
