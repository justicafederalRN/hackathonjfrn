<?php
namespace Processos;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class ProcessosModule extends \W5n\Module
{
    function init(\Application $app, Request $req, RouteCollection $routes)
    {
        $ui = $app['admin.ui_manager'];
        $ui->addMenu(
            'processos',
            'Processos',
            'gavel',
            $app->routeUrl('admin.crud', ['config' => 'processos'])
        );

        $ui->addMenu(
            'peticoes',
            'Petições Prioritárias',
            'warning',
            $app->routeUrl('admin.crud', ['config' => 'peticoes']),
            null,
            2
        );

        /* $ui->addSubmenu( */
        /*     'processos', */
        /*     'processos', */
        /*     'Processos', */
        /*     'gavel', */
        /*     $app->routeUrl('admin.crud', ['config' => 'processos']), */
        /*     null */
        /* ); */
    }

    public function initRoutes(RouteCollection $routes)
    {
        $routes->set(
            'admin.processos.detail',
            '/processos/detail/{id}',
            [
                'controller' => '\\Processos\\Controller\\AdminController',
                'action' => 'detail'
            ]
        );
    }
}
