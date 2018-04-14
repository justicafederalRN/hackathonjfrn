<?php

return array(
    'singular'       => 'Usuário',
    'plural'         => 'Usuários',
    'gender'         => 'm',
    'model'          => '\\Usuarios\\Model\\Usuario',
    'active_menu'    => 'usuarios',
    'table'              => array(
        'fields'           => array(
            'id'   => false,
            'nome' => 'Nome'
        ),
        'sort_fields'      => array(
            'nome',
            'cnpj',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento',
            'cep',
            'telefone',
            'tipo'
        ),
        'order_by'         => 'nome',
        'pagination_route' => 'admin/clientes/list',
        'empty_message'    => 'Nenhum Usuário'
    ),
    'search'    => array(
        'search' => array(
            'label' => 'Buscar',
            'type'  => 'text',
            'fields' => array(
                'nome'  => 'like'
            )
        )
    ),
    'form_layout'        => array(
        array(
            'nome' => 6, 'email' => 6
        ),
        array(
            'login' => 4, 'senha' => 4, 'confirmacao_senha' => 4
        ),
        array(
            'ativo' => 4
        )
    ),
);
