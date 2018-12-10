<?php

return [
    'sf' => [
        'standardUrl' => 'http://ucmp.sf-express.com/cx-wechat-order/order/address/intelAddressResolution?address=',
    ],

    'hituan' => [
        'searchUrl' => 'http://ht.haituan2017.com/'
    ],

    'yichadan' => [
        'detailUrl' => 'http://vip.yichadan.com/query/json'
    ],
    'baidu' => [
        'app_id' => env('BAIDU_APP_ID'),
        'api_key' => env('BAIDU_API_KEY'),
        'secret_key' => env('BAIDU_SECRET_KEY'),
    ]
];