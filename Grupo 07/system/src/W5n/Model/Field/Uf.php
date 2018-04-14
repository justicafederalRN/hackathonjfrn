<?php

namespace W5n\Model\Field;

class Uf extends Options
{
    protected static $estados = [
        ''   => '',
        'AC'=>'Acre',
        'AL'=>'Alagoas',
        'AP'=>'Amapá',
        'AM'=>'Amazonas',
        'BA'=>'Bahia',
        'CE'=>'Ceará',
        'DF'=>'Distrito Federal',
        'ES'=>'Espírito Santo',
        'GO'=>'Goiás',
        'MA'=>'Maranhão',
        'MT'=>'Mato Grosso',
        'MS'=>'Mato Grosso do Sul',
        'MG'=>'Minas Gerais',
        'PA'=>'Pará',
        'PB'=>'Paraíba',
        'PR'=>'Paraná',
        'PE'=>'Pernambuco',
        'PI'=>'Piauí',
        'RJ'=>'Rio de Janeiro',
        'RN'=>'Rio Grande do Norte',
        'RS'=>'Rio Grande do Sul',
        'RO'=>'Rondônia',
        'RR'=>'Roraima',
        'SC'=>'Santa Catarina',
        'SP'=>'São Paulo',
        'SE'=>'Sergipe',
        'TO'=>'Tocantins'
    ];

    public function __construct($name, $label, $defaultMessage = null, $emptyMessage = null, $fullName = true)
    {
        $options = array();
        if ($fullName) {
            $options = self::$estados;
        } else {
            $keys = array_keys(self::$estados);
            $options = array_combine($keys, $keys);
        }

        parent::__construct($name, $label, $options, $defaultMessage, $emptyMessage);
    }

    public static function getUfs($abbreviations = false)
    {
        if (!$abbreviations) {
            return self::$estados;
        }
        $keys    = array_keys(self::$estados);
        $options = array_combine($keys, $keys);

        return $options;
    }

}
