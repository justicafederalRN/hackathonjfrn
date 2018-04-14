<?php
namespace Configuracoes;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class ConfiguracoesModule extends \W5n\Module
{
    public function init(\Application $app, Request $req, RouteCollection $routes)
    {
        return;
        /* @var $ui \Admin\Ui\UiManager */
        $ui = $app['admin.ui_manager'];
        $ui->addMenu(
            'configuracoes',
            'Configurações',
            'cog',
            $app->routeUrl('admin.crud', ['config' => 'configuracoes']),
            null,
            INF
        );
    }

    public function initRoutes(RouteCollection $routes)
    {
        $usuario = \Usuarios\Model\Usuario::getLoggedUser();
        if (empty($usuario) || $usuario->id() != 1) {
            return;
        }
        parent::initRoutes($routes);
        $routes->set(
            'admin.configuracoes',
            'configuracoes',
            [
                'controller' => '\\Admin\\Controller\\CrudController',
                'action' => 'insert',
                'config' => 'configuracoes'
            ]
        );
    }
}
