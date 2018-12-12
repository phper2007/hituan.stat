<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\UserAddress;
use App\Models\Express;


class ExpressService
{

    public function updateExpress($productUrl, $userAddress)
    {
        $expressModel = new Express();

        $client = new Client();

        $md5String = md5($productUrl);

        $hasExpress = $expressModel->where('website_md5', $md5String)->first();

        if(!$hasExpress || $hasExpress['status'] != 'signed')
        {
            $res = $client->request('GET', $productUrl);
            $responseContent = $res->getBody();

            if(preg_match('/var no = \'([0-9]+)\'/', $responseContent, $matches) && preg_match('/\<title\>(.+)\<\/title\>/', $responseContent, $title))
            {

                $no = $matches[1];

                $interfaceUrl = config('external.yichadan.detailUrl');

                $params = [
                    'form_params' => [
                        'no' => $no,
                        'company' => 'unknown',
                    ]
                ];
                $res = $client->request('POST', $interfaceUrl, $params);
                $responseContent = $res->getBody();
                $expressData = json_decode($responseContent, true);

                /*print_r("<br >");
                print_r($expressData);*/

                $status = $expressData['status'] == 'fail' ? 'fail' : $expressData['data']['status'];

                if($expressData['data'] && isset($expressData['data']['data']))
                {
                    foreach ($expressData['data']['data'] as $val)
                    {
                        if(strpos($val['context'], '已签收') !== false
                            || strpos($val['context'], '代签收') !== false
                            || strpos($val['context'], '完成取件') !== false
                        )
                        {
                            $status = 'signed';
                            break;
                        }
                        elseif(strpos($val['context'], '取货码') !== false)
                        {
                            $status = 'signed';
                            break;
                        }
                    }
                }
                $data = [
                    'contact_phone' => $userAddress['contact_phone'],
                    'contact_name' => $userAddress['contact_name'],
                    'product_name' => $title[1],
                    'company_code' => $expressData['company']['code'],
                    'company_name' => $expressData['company']['name'],
                    'express_no' => $no,
                    'express_data' => json_encode($expressData['data']),
                    'status' => $status,
                    'website_url' => $productUrl,
                    'website_md5' => md5($productUrl),
                ];

                if(!$hasExpress || ($hasExpress && $status != $hasExpress['status']))
                {
                    $data['is_msg'] = 1;
                    $data['node_time'] = date('Y-m-d H:i:s');

                    (new UserAddress())->where('id', $userAddress['id'])->increment('msg_count');
                }

                $expressModel->updateOrCreate(['website_md5' => $md5String], $data);
            }
        }
    }

    public function searchExpress(UserAddress $userAddress)
    {
        $expressModel = new Express();

        $url = config('external.hituan.searchUrl');

        $client = new Client();

        $params = [
            'form_params' => [
                'action' => 'home_query',
                'query_str' => $userAddress->contact_phone,
            ]
        ];
        $res = $client->request('POST', $url, $params);
        $responseContent = $res->getBody();
        $expressData = json_decode($responseContent, true);

        if($expressData['count'] && $expressData['data'])
        {
            $userAddress = $userAddress->toArray();

            if($expressData['count'] == 1)
            {
                $expressData['data'] = [
                    [
                        'url' => $expressData['data'][0]
                    ]
                ];
            }

            $expressData['data'] = array_reverse($expressData['data']);
            foreach ($expressData['data'] as $item)
            {
                $this->updateExpress($item['url'], $userAddress);
            }
        }
    }
}
