<?php
namespace W5n\Model\Field\Validation;

use W5n\Entity;
use W5n\Model\Field\Field;
use W5n\Html\Tag;

/**
 * @method $this callback($callback, $errorMessage) Add Callback validation to this field
 * @method $this cnpj() Add Cnpj validation to this field
 * @method $this conditional($validation, $errorMessage, $callback) Add Conditional validation to this field
 * @method $this cpf() Add Cpf validation to this field
 * @method $this date($format = 'Y-m-d') Add Date validation to this field
 * @method $this dateGreaterThan($otherDateField, $allowEquals = true) Add DateGreaterThan validation to this field
 * @method $this dateLesserThan($otherDateField, $allowEquals = true) Add DateLesserThan validation to this field
 * @method $this datetime($format = 'Y-m-d') Add Datetime validation to this field
 * @method $this dun14() Add Dun14 validation to this field
 * @method $this ean13() Add Ean13 validation to this field
 * @method $this email() Add Email validation to this field
 * @method $this exists($fkTable, $fkField, $db = null) Add Exists validation to this field
 * @method $this greaterThan($otherDateField, $allowEquals = true) Add GreaterThan validation to this field
 * @method $this imageExactHeight($height, $errorMessage = null) Add ImageExactHeight validation to this field
 * @method $this imageExactWidth($width, $errorMessage = null) Add ImageExactWidth validation to this field
 * @method $this imageMaxDimension($dimension) Add ImageMaxDimension validation to this field
 * @method $this imageMaxHeight($height, $errorMessage = null) Add ImageMaxHeight validation to this field
 * @method $this imageMaxSize($size, $errorMessage = null) Add ImageMaxSize validation to this field
 * @method $this imageMaxWidth($width, $errorMessage = null) Add ImageMaxWidth validation to this field
 * @method $this imageMinHeight($height, $errorMessage = null) Add ImageMinHeight validation to this field
 * @method $this imageMinWidth($width, $errorMessage = null) Add ImageMinWidth validation to this field
 * @method $this inSet($set) Add InSet validation to this field
 * @method $this integer() Add Integer validation to this field
 * @method $this lessThan($otherDateField, $allowEquals = true) Add LessThan validation to this field
 * @method $this mandatory($callback = null) Add Mandatory validation to this field
 * @method $this mandatoryOnInsert() Add MandatoryOnInsert validation to this field
 * @method $this mandatoryOnUpdate() Add MandatoryOnUpdate validation to this field
 * @method $this match($otherPasswordFieldName, $ignoreOnEmpty = true) Add Match validation to this field
 * @method $this max($value, $allowEquals = true) Add Max validation to this field
 * @method $this maxCount($value, $allowEquals, $errorMessage) Add MaxCount validation to this field
 * @method $this maxLength($maxLength) Add MaxLength validation to this field
 * @method $this min($value, $allowEquals = true) Add Min validation to this field
 * @method $this minCount($value, $allowEquals, $errorMessage) Add MinCount validation to this field
 * @method $this minLength($maxLength) Add MinLength validation to this field
 * @method $this multiline($validation, $errorMessage = null, $lineSeparator = "\n") Add Multiline validation to this field
 * @method $this number() Add Number validation to this field
 * @method $this regex($regex) Add Regex validation to this field
 * @method $this toggleEnabled($fields = [], $otherSelectors = []) Add ToggleEnabled validation to this field
 * @method $this toggleVisible($fields = [], $otherSelectors = []) Add ToggleVisible validation to this field
 * @method $this unique($fields = null, $modifyQueryCallback = null, $modifyFieldsCallback = null) Add Unique validation to this field
 * @method $this url() Add Url validation to this field
 * @method $this validateImage() Add ValidateImage validation to this field
 * @method $this validation() Add Validation validation to this field
 */
abstract class Validation extends Entity
{

    public function __construct()
    {

    }

    abstract function validate($value, Field $field, $operation);
    abstract function getErrorMessage(Field $field, $operation);

    public function skipIfEmpty()
    {
        return true;
    }

    protected function genderString(Field $field, $maleString, $femaleString)
    {
        $isMale = $field->getGender() != Field::GENDER_FEMALE;

        return $isMale ? $maleString : $femaleString;
    }

    public function beforeRenderField(Tag $html, Field $field) {}
}
