<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Order;
use App\Models\Express;

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

        if($expressData['count'] && $expressData['data'])
        {
            $expressData['data'] = array_reverse($expressData['data']);
            foreach ($expressData['data'] as $item)
            {
                $md5String = md5($item['url']);

                $hasExpress = $expressModel->where('website_md5', $md5String)->first();

                if(!$hasExpress || $hasExpress['status'] != 'signed')
                {
                    $res = $client->request('GET', $item['url']);
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

                        /*print_r("<br >");
                        print_r($expressData);*/


                        $data = [
                            'contact_phone' => $user_address->contact_phone,
                            'product_name' => $item['product'],
                            'company_code' => $expressData['company']['code'],
                            'company_name' => $expressData['company']['name'],
                            'express_no' => $no,
                            'express_data' => json_encode($expressData['data']),
                            'status' => $expressData['data']['status'],
                            'website_url' => $item['url'],
                            'website_md5' => md5($item['url']),
                        ];

                        $expressModel->updateOrCreate(['website_md5' => $md5String], $data);
                    }
                }
            }
        }
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
