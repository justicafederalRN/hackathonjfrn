<?php
namespace W5n\Model\Field\Validation;

class Multiline extends \W5n\Model\Field\Validation\Validation
{
    const ERROR_LINES_PLACEHOLDER = '%lines%';
    
    public function __construct(Validation $validation, $errorMessage = null, $lineSeparator = "\n")
    {
        
        $this->setOption('validation', $validation);
        $this->setOption('errorMessage', $errorMessage);
        $this->setOption('lineSeparator', $lineSeparator);
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        $errorMessage = $this->getOption('errorMessage');
        $errorLines   = $this->getOption('errorLines');
        if (!empty($errorLines)) {
            $prefix = count($errorLines) > 1 ? 'Linhas: ' : 'Linha: ';
            $errorLines = '(' . $prefix . implode(', ', $errorLines) . ')';
        } else {
            $errorLines = '';
        }
        
        
        if (!empty($errorMessage)) {
            return str_replace(self::ERROR_LINES_PLACEHOLDER, $errorLines, $errorMessage);
        } else {
            return '1 ou mais valores não são válidos' . $errorLines . '.';
        }
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $validation = $this->getOption('validation');
        $lines      = explode($this->getOption('lineSeparator', "\n"), $value);
        
        $errorLines = array();
        $i = 1;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$validation->validate($line, $field, $operation)) {
                $errorLines[] = $i;
            }
            $i++;
        }
        if (!empty($errorLines)) {
            $this->setOption('errorLines', $errorLines);
            return false;
        }
        return true;
    }
}
