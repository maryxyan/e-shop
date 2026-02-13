<?php

return [
    'shipping_token' => env('SHIPPING_API_TOKEN'),
    'name' => env('SHOP_NAME', 'DMG SHOP'),
    'country' => env('SHOP_COUNTRY_ISO', 'RO'),
    'country_id' => env('SHOP_COUNTRY_ID', 208),
    'weight' => env('SHOP_WEIGHT', 'kg'),
    'email' => env('SHOP_EMAIL', 'office@dmgart.ro'),
    'phone' => env('SHOP_PHONE', ' 0727 583 963'),
    'warehouse' => [
        'address_1' => 'Strada Grivitei 22',
        'address_2' => '',
        'state' => 'Bucuresti',
        'city' => 'Bucuresti',
        'country' => 'RO',
        'zip' => '94043',
    ],
    'social_facebook' => env('SHOP_SOCIAL_FACEBOOK', ''),
    'social_instagram' => env('SHOP_SOCIAL_INSTAGRAM', ''),
];