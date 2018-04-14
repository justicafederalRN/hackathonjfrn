<?php
namespace Admin\Controller\Base;

use Admin\Ui\UiManager;
use \Usuarios\Model\Usuario;

class AdminController extends \W5n\Controller\DefaultController
{
    protected $layout    = 'layout/login';
    protected $ui        = null;
    protected $appConfig = null;
    private $loggedUser = null;

    public function __construct(\Application $app, \Symfony\Component\HttpFoundation\Request $request)
    {
        parent::__construct($app, $request);

        $this->setUiManager($app['admin.ui_manager']);
        $this->appConfig = $this->getApplication()->loadConfig('app');

        if (!Usuario::isLoggedIn()) {
            Usuario::logout();
            $this->redirectToLogin()->send();
            return;
        }

        $this->loggedUser = Usuario::getLoggedUser();

        if (isset($this->appConfig['name'])) {
            $this->ui->setProjectName($this->appConfig['name']);
            $this->ui->setUsername($this->loggedUser->nome);
        }

        $this->init();
    }

    protected function redirectToLogin()
    {
        return $this->routeRedirect('admin.auth', ['action' => 'login']);
    }

    public function init() {}

    public function modifyView(\W5n\Templating\View $view)
    {
        parent::modifyView($view);
        $view->ui = $this->getUiManager();
        $view->loggedUser = $this->getLoggedUser();
    }

    /**
     * @return UiManager
     */
    public function getUiManager()
    {
        return $this->ui;
    }

    public function setUiManager(UiManager $ui)
    {
        $this->ui = $ui;
        return $this;
    }

    public function getLoggedUser()
    {
        return $this->loggedUser;
    }

}
