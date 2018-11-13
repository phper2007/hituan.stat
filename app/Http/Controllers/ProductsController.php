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

        $productList = $productModel->where('product_date', $maxDate)->get();

        return view('products.index', compact('productList'));
    }

    public function import()
    {
        return view('products.import');
    }

    public function importAnalysis(Request $request)
    {
        $content = $request->input('content');

        $productList = [];
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
            elseif ($groupDate && preg_match("/{$productSerialNumber[$num]}(.*)/", $val, $matches))
            {
                $productList[$num] = $matches[1];
                $num++;
            }
            elseif ($groupDate && preg_match("/{$num}\.(.*)/", $val, $matches))
            {
                $productList[$num] = $matches[1];
                $num++;
            }
            elseif ($groupDate && strpos($val, '我要') === 0)
            {
                break;
            }

            /*print_r($val);
            print_r("<hr />");*/
        }
        /*print_r($arr);
        print_r("<hr />");
        print_r(date('Y-m-d', $groupDate));
        print_r($productList);*/

        return view('products.import_analysis', compact('productList', 'groupDate'));
    }

    public function storeImport(ProductRequest $request)
    {
        $groupDate = $request->input('groupDate');
        $names = $request->input('name');
        $sell_price = $request->input('sell_price');
        $cost_price = $request->input('cost_price');

        $data = [];
        foreach ($names as $key => $name)
        {
            $data[] = [
                'product_date' => date('Y-m-d', $groupDate),
                'name' => $name,
                'sell_price' => $sell_price[$key] ? $sell_price[$key] : 0,
                'cost_price' => $cost_price[$key] ? $cost_price[$key] : 0,
            ];
        }

        $result = (new Product())->insert($data);

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
