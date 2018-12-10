<?php

return [
    //接龙信息
    'productSerialNumber' => [
        1 => '①',
        2 => '②',
        3 => '③',
        4 => '④',
        5 => '⑤',
        6 => '⑥',
        7 => '⑦',
        8 => '⑧',
        9 => '⑨',
        10 => '⑩',
        11 => '⑪',
        12 => '⑫',
        13 => '⑬',
        14 => '⑭',
        15 => '⑮',
        16 => '⑯',
        17 => '⑰',
        18 => '⑱',
        19 => '⑲',
        20 => '⑳',
    ],
    
    'orderCountDict' => array_combine(range(1, 100), range(1, 100)),

    'orderDetailCountDict' => array_combine(range(0, 100), range(0, 100)),

    'expressStatusDict' => [
        'fail' => '未找到运单',
        'transit' => '运输中',
        'signed' => '已签收',
    ],
];

