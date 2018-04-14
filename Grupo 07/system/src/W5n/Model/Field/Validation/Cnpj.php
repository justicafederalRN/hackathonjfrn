<?php
namespace W5n\Model\Field\Validation;

class Cnpj extends \W5n\Model\Field\Validation\Validation
{
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Número de CNPJ inválido';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $cnpj = preg_replace( '@[^0-9]@', '', $value);
        if( strlen( $cnpj ) != 14 or !is_numeric( $cnpj ) ) {
            return false;
        }
        $k = 6;
        $soma1 = "";
        $soma2 = "";
        for( $i = 0; $i < 13; $i++ ) {
            $k = $k == 1 ? 9 : $k;
            $soma2 += ( $cnpj{$i} * $k );
            $k--;
            if($i < 12) {
                if($k == 1) {
                    $k = 9;
                    $soma1 += ( $cnpj{$i} * $k );
                    $k = 1;
                }
                else {
                    $soma1 += ( $cnpj{$i} * $k );
                }
            }
        }

        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
        return ( $cnpj{12} == $digito1 and $cnpj{13} == $digito2 );
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-vat', 'true');
        $html->setAttr('data-fv-vat-country', 'BR');
        $html->setAttr('data-fv-vat-message', 'CNPJ Inválido');
    }

}