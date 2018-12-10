<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Order;
use App\Models\Express;
use App\Services\ExpressService;

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
        $expressModel = new Express();

        //手动更新
        (new ExpressService())->searchExpress($user_address);

        /*print_r("<br >");
        print_r($data);*/

        $expressList = $expressModel
            ->where('contact_phone', $user_address->contact_phone)
            ->orderBy('id', 'desc')
            ->get();

        return view('expresses.search', [
            'address' => $user_address,
            'expressList' => $expressList,
            'expressStatusDict' => config('project.expressStatusDict'),
        ]);
    }

    public function analysis()
    {
        $url = 'http://ht.haituan2017.com/3be09/15801293509';

        $client = new Client();

        $res = $client->request('GET', $url);
        $responseContent = $res->getBody();

        if(preg_match('/var no = \'([0-9]+)\'/', $responseContent, $matches))
        {

            $no = $matches[1];

            $url = config('external.yichadan.detailUrl');

            $params = [
                'form_params' => [
                    'no' => $no,
                    'company' => 'unknown',
                ]
            ];
            $res = $client->request('POST', $url, $params);
            $responseContent = $res->getBody();
            $expressData = json_decode($responseContent, true);

            print_r("<br >");
            print_r($expressData);

        }

        return;
    }
}
