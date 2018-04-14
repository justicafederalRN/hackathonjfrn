<?php
namespace W5n\Model\Field\Validation;

class Conditional extends \W5n\Model\Field\Validation\Validation
{
    protected $callback;
    protected $errorMessage;
    protected $validation;
    
    public function __construct(
        \W5n\Model\Field\Validation\Validation $validation,
        $errorMessage,
        $callback
    ) {
        if (!is_callable($callback, FALSE, $callback_call)) {
            throw new \W5n\Model\Exception('Invalid callback: ' . $callback_call);
        }
        $this->callback     = $callback;
        $this->errorMessage = $errorMessage;
        $this->validation   = $validation;
    }
    
    public function skipIfEmpty()
    {
        return false;
    }
    
    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $result = call_user_func_array($this->callback, func_get_args());
        if ($result === true) {
            return $this->validation->validate($value, $field, $operation);
        }
        return true;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->errorMessage;
    }

}