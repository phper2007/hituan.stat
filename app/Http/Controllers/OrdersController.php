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

    public function create(UserAddress $user_address)
    {
        $order = new Order();
        $order->sell_count = 1;

        return view('orders.create_and_edit', [
            'address' => $user_address,
            'productRelate' => $this->productRelate,
            'order' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'address_id',
            'product_id',
            'sell_count',
            'detail_name1',
            'detail_name2',
        ]);

        //完善信息
        $userAddress = (new UserAddress())->find($data['address_id']);
        $product = (new Product())->find($data['product_id']);

        $data['product_date'] = $product['product_date'];
        $data['product_name'] = $product['name'];
        $data['sell_price'] = $product['sell_price'];
        $data['cost_price'] = $product['cost_price'];
        $data['province'] = $userAddress['province'];
        $data['city'] = $userAddress['city'];
        $data['district'] = $userAddress['district'];
        $data['address'] = $userAddress['address'];
        $data['contact_name'] = $userAddress['contact_name'];
        $data['contact_phone'] = $userAddress['contact_phone'];

        $data['detail_name3'] = '';
        $data['detail_name1'] = $data['detail_name1'] ? $data['detail_name1'] : '';
        $data['detail_name2'] = $data['detail_name2'] ? $data['detail_name2'] : '';

        $id = (new Order())->insertGetId($data);

        return redirect()->route('orders.check', ['order' => $id]);
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
