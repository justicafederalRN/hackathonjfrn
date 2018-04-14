<?php
namespace W5n\Model\Field\Validation;

class Callback extends \W5n\Model\Field\Validation\Validation
{
    protected $callback;
    protected $errorMessage;

    public function __construct($callback, $errorMessage)
    {
        if (!is_callable($callback, FALSE, $callback_call)) {
            throw new \W5n\Model\Exception('Invalid callback: '.$callback_call);
        }
        $this->callback     = $callback;
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->errorMessage;
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $result = call_user_func($this->callback, $value, $field, $operation);
        return (bool)$result;
    }

}
