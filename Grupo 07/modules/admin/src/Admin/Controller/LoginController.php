<?php
namespace Admin\Controller;

use Usuarios\Model\Usuario;

class LoginController extends \W5n\Controller\DefaultController
{
    public function loginAction()
    {
        if (Usuario::isLoggedIn()) {
            return $this->routeRedirect('admin.dashboard');
        }

        if ($this->getRequest()->isMethod('POST')) {
            $login   = $this->getRequest()->request->get('login');
            $senha   = $this->getRequest()->request->get('senha');
            $lembrar = $this->getRequest()->request->get('lembrar', false);

            if (empty($login) || empty($senha)) {
                $this->addErrorFlash('O login e a senha devem ser informados.');
            } else {
                if (Usuario::login($login, $senha, $lembrar)) {
                    return $this->routeRedirect('admin.dashboard');
                } else {
                    $this->addErrorFlash('Login ou senha inválidos.');
                }
            }
        }

        $app = $this->getApplication();

        $data = [
            'appConfig' => $app->loadConfig('app'),
            'login'     => $this->getRequest()->request->get('login', '')
        ];

        return $this->createView('admin/login', $data)->render();
    }

    public function logoutAction()
    {
        Usuario::logout();
        return $this->routeRedirect('admin.auth', ['action' => 'login']);
    }

}
