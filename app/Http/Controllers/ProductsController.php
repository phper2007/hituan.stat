<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $productModel = (new Product());

        $maxDate = $productModel->getMaxDate();

        $productList = $productModel->where('group_date', $maxDate)->get();

        return view('products.index', compact('productList'));
    }

    public function importOffer()
    {
        return view('products.import_offer', [
            'productSerialNumber' => config('project.productSerialNumber')
        ]);
    }

    public function importOfferAnalysis(Request $request)
    {
        $content = $request->input('content');

        $productList = $costPrice = $sellPrice = $orderFormat = $productNumber = $productUnit = [];
        $groupDate = '';

        $arr = array_filter(array_map('trim', explode("\r\n", $content)));

        $productSerialNumber = config('project.productSerialNumber');

        $num = 1;
        //echo "<pre>";
        foreach ($arr as $val)
        {
            $matches = [];
            //艾灸坐垫，分销58包邮，团购75元包邮1个‼️新疆西藏内蒙每个➕5，嗨团云仓售后1
            //①新版神洗2瓶分销64包邮，双十一专享价89元包邮2瓶‼️浙江百世邮政售后3

            $pattern = "/{$productSerialNumber[$num]}(.*)分销[^0-9]?[^0-9]?[^0-9]?([0-9]{1,4}).*[专|团].[享|购].[^0-9]?[^0-9]?[^0-9]?([0-9]{1,4})/";

            if(!$groupDate && preg_match("/([0-9]{1,2})\.([0-9]{1,2}).*/", $val, $matches))
            {
                /*print_r($matches);
                print_r("<hr />");*/
                $groupDate = mktime(0, 0, 0, $matches[1], $matches[2], date('Y'));
            }
            elseif ($groupDate && preg_match($pattern, $val, $matches))
            {
                //print_r($matches);
                $productList[$num] = str_replace('，', '', $matches[1]);

                $orderFormat[$num] = $matches[1]. '1个';
                $costPrice[$num] = $matches[2];
                $sellPrice[$num] = $matches[3];
                $productNumber[$num] = 1;
                $productUnit[$num] = '个';

                $num++;
            }

            if ($groupDate && strpos($val, '下单格式') !== false)
            {
                break;
            }

            /*print_r($val);
            print_r("<hr />");*/
        }


        //return;
        $begin = false;
        $num = 1;
        //echo '<pre>';
        foreach ($arr as $val)
        {
            if (!$begin && strpos($val, '下单格式') !== false)
            {
                $begin = true;
                continue;
            }
            $matches = [];
            //艾灸坐垫1个。1
            $pattern = "/(.*)[0-9]{1,4}([^0-9]*)。([0-9]{1,4})/";

            if (preg_match($pattern, $val, $matches))
            {
                if(!isset($productList[$num]))
                {
                    echo $num;
                    break;
                }
                //print_r($matches);

                $orderFormat[$num] = $matches[1] . "%d" . $matches[2];
                $productNumber[$num] = $matches[3];
                $productUnit[$num] = $matches[2];

                $num++;
            }
        }

        /*print_r(compact(
            'productList',
            'orderFormat',
            'costPrice',
            'sellPrice',
            'productNumber',
            'productUnit',
            'groupDate'
        ));
        return;*/
        /*print_r($arr);
        print_r("<hr />");
        print_r(date('Y-m-d', $groupDate));
        print_r($productList);*/

        return view('products.import_analysis', compact(
            'productList',
            'costPrice',
            'sellPrice',
            'orderFormat',
            'productNumber',
            'productUnit',
            'groupDate'
        ));
    }

    public function importSolitaire()
    {
        return view('products.import_solitaire');
    }

    public function importSolitaireAnalysis(Request $request)
    {
        $content = $request->input('content');

        $productList = $costPrice = $sellPrice = $orderFormat = $productNumber = $productUnit = [];
        $groupDate = '';

        $arr = array_filter(array_map('trim', explode("\r\n", $content)));

        $productSerialNumber = config('project.productSerialNumber');

        $num = 1;
        foreach ($arr as $val)
        {
            $matches = [];

            if(!$groupDate && preg_match("/([0-9]{1,2})\.([0-9]{1,2}).*/", $val, $matches))
            {
                /*print_r($matches);
                print_r("<hr />");*/
                $groupDate = mktime(0, 0, 0, $matches[1], $matches[2], date('Y'));
            }
            elseif (
                ($groupDate && preg_match("/{$productSerialNumber[$num]}(.*)/", $val, $matches))
            || ($groupDate && preg_match("/{$num}\.(.*)/", $val, $matches))
            )
            {
                $productList[$num] = $matches[1];

                $orderFormat[$num] = $matches[1]. '1个';
                $costPrice[$num] = '';
                $sellPrice[$num] = '';
                $productNumber[$num] = 1;
                $productUnit[$num] = '个';
                
                $num++;
            }

            if ($groupDate && strpos($val, '我要') === 0)
            {
                break;
            }

            /*print_r($val);
            print_r("<hr />");*/
        }
        /*print_r(compact(
            'productList',
            'orderFormat',
            'costPrice',
            'sellPrice',
            'productNumber',
            'productUnit',
            'groupDate'
        ));
        return;*/
        /*print_r($arr);
        print_r("<hr />");
        print_r(date('Y-m-d', $groupDate));
        print_r($productList);*/

        return view('products.import_analysis', compact(
            'productList',
            'costPrice',
            'sellPrice',
            'orderFormat',
            'productNumber',
            'productUnit',
            'groupDate'
        ));
    }

    public function storeImport(ProductRequest $request)
    {
        $groupDate = $request->input('groupDate');
        $names = $request->input('name');
        $sellPrice = $request->input('sell_price');
        $costPrice = $request->input('cost_price');
        $orderFormat = $request->input('order_format');
        $productNumber = $request->input('product_number');
        $productUnit = $request->input('product_unit');

        $productModel = new Product();

        foreach ($names as $key => $name)
        {
            $data = [
                'group_date' => date('Y-m-d', $groupDate),
                'name' => $name,
                'sell_price' => $sellPrice[$key],
                'cost_price' => $costPrice[$key],
                'order_format' => $orderFormat[$key],
                'product_number' => $productNumber[$key],
                'product_unit' => $productUnit[$key],
            ];

            $result = $productModel->create($data);
        }


        //print_r($result);

        return redirect()->route('products.index');
    }

    public function create()
    {
        return view('products.create_and_edit', ['product' => new Product()]);
    }

    public function store(ProductRequest $request)
    {
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'product',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.create_and_edit', ['product' => $product]);
    }

    public function update(Product $product, ProductRequest $request)
    {
        $product->update($request->only([
            'name',
            'cost_price',
            'sell_price',
            'order_format',
            'product_number',
            'product_unit',
        ]));

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        // 把之前的 redirect 改成返回空数组
        return [];
    }
}
