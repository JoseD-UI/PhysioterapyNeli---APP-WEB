<?php
return [
    'igv_percent' => 0.18,
    'default_tipo' => '03', // por defecto boleta
    'default_serie' => [
        '01' => 'F001',
        '03' => 'B001',
        '12' => 'T001'
    ],
    'electronic' => [
        'enabled' => false,
        'provider' => env('FACT_E_PROVIDER', null) // nublefact, ose, etc.
    ]
];
