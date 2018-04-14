<?php
namespace W5n\Model\Field\Validation;

class Cpf extends \W5n\Model\Field\Validation\Validation
{
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Número de CPF inválido';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $cpf = preg_replace('#[^0-9]#', '', $value);

        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-id', 'true');
        $html->setAttr('data-fv-id-country', 'BR');
        $html->setAttr('data-fv-id-message', 'CPF Inválido');
    }

}