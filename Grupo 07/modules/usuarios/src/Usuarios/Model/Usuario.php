<?php
namespace Usuarios\Model;

use \Auth\AuthableModel;

class Usuario extends \W5n\Model\Model
{
    use AuthableModel;

    protected $table     = 'usuarios';
    protected $sequence  = 'usuarios_id_seq';

    //auth config
    protected $authLoginColumn    = 'login';
    protected $authPasswordColumn = 'senha';

    public function init()
    {
        $this->text('nome', 'Nome')->mandatory();
        $this->text('login', 'Login')->mandatory()->unique();
        $this->email('email', 'E-mail')->mandatory()->unique();
        $this->boolean('ativo', 'Ativo');
        $this->password('senha', 'Senha')->mandatoryOnInsert();
        $this->password('confirmacao_senha', 'Confirmar senha')
            ->mandatoryOnInsert()
            ->match('senha')
            ->transient();
        //$this->contrato_id = \Usuarios\Model\Usuario::getLoggedUser()->contrato_id;
    }

    public static function modifyFindQueryBuilder(\Doctrine\DBAL\Query\QueryBuilder $query)
    {
        $query->andWhere('visivel=1');
    }
}
