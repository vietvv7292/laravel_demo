<?php

return [

    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [


        // pusher trên https://dashboard.pusher.com/apps 
        // 'pusher' => [
        //     'driver' => 'pusher',
        //     'key' => env('PUSHER_APP_KEY'),
        //     'secret' => env('PUSHER_APP_SECRET'),
        //     'app_id' => env('PUSHER_APP_ID'),
        //     'options' => [
        //         'cluster' => env('PUSHER_APP_CLUSTER'),
        //         'useTLS' => true,
        //     ],
        // ],

        // pusher tự cấu hình trên server
        // 'pusher' => [
        //     'driver' => 'pusher',
        //     'key' => env('REVERB_APP_KEY'),
        //     'secret' => env('REVERB_APP_SECRET', ''),  // <== dùng chuỗi rỗng thay vì null
        //     'app_id' => env('REVERB_APP_KEY'),
        //     'options' => [
        //         'host' => env('REVERB_HOST'),
        //         'port' => env('REVERB_PORT'),
        //         'scheme' => env('REVERB_SCHEME', 'http'),
        //         'encrypted' => false,
        //         'useTLS' => env('REVERB_SCHEME') === 'https',
        //     ],
        // ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'host' => env('PUSHER_HOST', 'soketi'),
                'port' => env('PUSHER_PORT', 6001),
                'scheme' => env('PUSHER_SCHEME', 'http'),
                'useTLS' => false,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
