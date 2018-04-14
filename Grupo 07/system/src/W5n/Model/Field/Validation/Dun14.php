<?php
namespace W5n\Model\Field\Validation;

class Dun14 extends Validation
{

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Código de barras inválido';
    }

    public function validate($barcode, \W5n\Model\Field\Field $field, $operation)
    {
        //TODO: saber se valida com EAN14 ou ITF-14
        return true;
    }

}
