<?php

return [
    'singular'    => 'Petição Prioritária',
    'plural'      => 'Petições Prioritárias',
    'gender'      => 'f',
    'model'       => '\\Processos\\Model\\Peticao',
    'active_menu' => 'peticoes',
    'after_load_config' => function ($controller) {
        $controller->addRowAction(
            'details',
            function ($data) use ($controller) {
                return $controller->getRouteUrl('admin.processos.detail', ['id' => $data['id']]);
            },
            'Detalhes',
            'eye',
            [],
            -1000
        );
    },
    'table'       => [
        'fields'           => [
            'processos.id'    => false,
            'numero_processo' => '# Processo',
        ],
        'sort_fields'      => [
            'id',
            'numero_processo'
        ],
        'order_by'         => 'numero_processo',
        'empty_message'    => 'Nenhum Processo',
        'hidden' => [
            'id'
        ],
        'before_execute' => function ($query, $isCount) {
            if (!$isCount) {
                $query->addSelect(
                    'ass.nome as assunto',
                    'cp.nome as classe_processual',
                    'processos.procedente'
                );
            }
        },
        'labels' => [
            'parte_re'     => 'Parte Ré',
            'parte_autora' => 'Parte Autora',
            'classe_processual' =>'Classe Processual',
            'assunto' => 'Assunto',
            'procedente' => 'Procedência',
        ],
        'filters' => [
            'procedente' => function($p) {
                if ($p == '-') {
                    return '<span class="label label-warning">Aberto</span>';
                } elseif ($p == 'P') {
                    return '<span class="label label-success">Procedente</span>';
                } elseif ($p == 'I') {
                    return '<span class="label label-danger">Improcedente</span>';
                }

            }
        ]
    ],
    'search'      => [
        'search' => [
            'label'  => 'Buscar',
            'type'   => 'text', //date, options, db_options, text, boolean TODO: exibir mostrando 1:10 de 30
            'fields' => [
                'numero_processo' => 'like'
            ]
        ]
    ],
    'form_layout' => [
    ],
    'can_edit'   => false,
    'can_delete' => false,
];
