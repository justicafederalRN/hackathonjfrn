<?php

return [
    'login'     => 'consulte.consultoria',
    'password'  => 'consulte123',
    'baseUrl'   => 'http://www.facilitamovel.com.br/api/',
    'endpoints' => [
        'send' => [
            'uri' => 'simpleSend.ft',
            'params' => [
                'user', 'password', 'destinatario', 'msg', 'externalkey', 'day',
                'month', 'year'
            ]
        ],
        'credit' => [
            'uri' => 'checkCredit.ft'
        ]
    ]
];
