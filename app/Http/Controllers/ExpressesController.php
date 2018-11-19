<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Order;

class ExpressesController extends Controller
{

    protected $productRelate = [];

    public function __construct()
    {
        $productModel = (new Product());

        $this->productRelate = $productModel->getRelateByDate();
    }

    public function search(UserAddress $user_address)
    {
        $url = config('external.hituan.searchUrl');

        $client = new Client();

        $params = [
            'form_params' => [
                'action' => 'home_query',
                'query_str' => $user_address->contact_phone,
            ]
        ];
        $res = $client->request('POST', $url, $params);
        $responseContent = $res->getBody();
        $expressData = json_decode($responseContent, true);

        /*print_r("<br >");
        print_r($data);*/

        return view('expresses.search', [
            'address' => $user_address,
            'expressData' => $expressData,
        ]);
    }

}
