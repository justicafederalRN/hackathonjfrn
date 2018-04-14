<?php
use Configuracoes\Model\Configuracao as C;

return [
    'singular'    => 'Configurações',
    'plural'      => 'Configurações',
    'gender'      => 'm',
    'model'       => '\\Configuracoes\\Model\\Configuracao',
    'active_menu' => 'configuracoes',
    'table'       => [
        'fields'           => [
            'id'    => false,
            'chave' => 'Chave',
            'valor' => 'Valor',
        ],
        'sort_fields'      => [
            'id',
            'chave',
            'valor',
        ],
        'order_by'         => 'chave',
        'pagination_route' => 'admin/configuracoes/list',
        'empty_message'    => 'Nenhum Configuracoe'
    ],
    'search'      => [
        'search' => [
            'label'  => 'Buscar',
            'type'   => 'text', //date, options, db_options, text, boolean TODO: exibir mostrando 1:10 de 30
            'fields' => [
                'chave' => 'like'
            ]
        ]
    ],
    'form_layout' => [
        [C::KEY_DIAS_ENVIO_EMAIL  => 6, C::KEY_DIAS_ENVIO_SMS => 6],
        [C::KEY_NOTIFICACAO_SMS_TEXTO => 12],
        [C::KEY_NOTIFICACAO_EMAIL_TITULO => 12],
        [C::KEY_NOTIFICACAO_EMAIL_TEXTO  => 12]
    ],
    'add_backbutton' => false,
    'add_breadcrumb' => false,
    'add_subtitle'   => false,
    'insert_success_message' => 'Configurações salvas com sucesso.'
];
