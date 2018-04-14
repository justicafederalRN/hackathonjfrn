<?php
namespace Usuarios\Controller\Admin;

class UsuariosController extends \Admin\Controller\AdminController
{
    protected $layout = 'layout/admin';

    public function editProfileAction()
    {
        $this->getUiManager()->setPageTitle('Alterar Dados');
        return $this->handleForm(['nome', 'email'], [
            ['nome' => 6, 'email' => 6],
        ], 'Alterar Perfil', 'Perfil Alterado com Sucesso.');
    }

    public function changePasswordAction()
    {
        $this->getUiManager()->setPageTitle('Alterar Senha');


        $model = $this->getLoggedUser();
        $currentPass = $model->senha;

        $model->getField('senha')->setLabel('Nova senha')->mandatory();
        $model->getField('confirmacao_senha')->setLabel('Confirmar nova senha')->mandatory();
        $model->password('senha_atual', 'Senha Atual')->mandatory()->callback(function () use ($currentPass, $model) {
            $senha = $model->getField('senha_atual')->getOption('originalValue');
            return \Usuarios\Model\Usuario::authCheck($senha, $currentPass);
        }, 'Senha atual incorreta.')->setPersistent(false);


        return $this->handleForm(['senha', 'confirmacao_senha', 'senha_atual'], [
            ['senha_atual' => 4, 'senha' => 4, 'confirmacao_senha' => 4],
        ], 'Alterar Senha', 'Senha Alterada com Sucesso.');
    }

    private function handleForm(array $fields, $layout, $submitLabel, $successMessage)
    {
        $model = $this->getLoggedUser();
        $form  = new \W5n\Model\Form($model, $this->getRequest()->getUri(), 'POST');

        $data = [
            'model'       => $model,
            'form'        => $form,
            'formLayout'  => $layout,
            'configKey'   => 'usuarios',
            'listRoute'   => 'admin.dashboard',
            'backButton'  => false,
            'submitLabel' => $submitLabel
        ];

        if ($this->getRequest()->isMethod('POST')) {
            $modelData = [];
            foreach ($fields as $f) {
                $modelData[$f] = $this->getRequest()->request->get($f);
            }

            $model->populateFromArray($modelData);

            if ($model->save()) {
                $this->addSuccessFlash($successMessage);
                \Usuarios\Model\Usuario::authRefresh();
                return $this->currentRouteRedirect();
            } else {
                $this->addErrorFlash('Corrija os erros informados.');
            }
        }

        return $this->createView('admin/default_form', $data);

    }
}
