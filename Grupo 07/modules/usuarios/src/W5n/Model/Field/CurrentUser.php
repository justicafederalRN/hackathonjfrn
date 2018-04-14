<?php
namespace W5n\Model\Field;

class CurrentUser extends \W5n\Model\Field\Integer
{
    public function __construct($name)
    {
        parent::__construct($name, $name);
        $this->exists('usuarios', 'id');
        $this->mandatory();
        $this->setValue(\Usuarios\Model\Usuario::getLoggedUser()->id());
    }
}
