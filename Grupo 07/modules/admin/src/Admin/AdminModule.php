<?php
namespace Admin;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class AdminModule extends \W5n\Module
{
    public function initRoutes(\W5n\Routing\RouteCollection $routes)
    {
        parent::initRoutes($routes);

        $routes->set('admin.dashboard', 'dashboard', [
            'controller' => '\\Admin\\Controller\\DashboardController',
            'action'     => 'index'
        ])->setPriority(-100);

        $routes->set(
            'admin.auth',
            '{action}',
            [
                'controller' => '\\Admin\\Controller\\LoginController',
                'action'     => 'login'
            ],
            [
                'action' => '(login|logout)'
            ]
        )->setPriority(-100);

        $routes->set(
            'admin.crud.nested', '{parentConfig}/{parentId}/{config}/{action}', [
                'controller' => '\\Admin\\Controller\\NestedController',
                'action'     => 'index'
            ]
        )->setPriority(-199);
        $routes->set(
            'admin.crud', '{config}/{action}', [
                'controller' => '\\Admin\\Controller\\CrudController',
                'action'     => 'index'
            ]
        )->setPriority(-200);
        $routes->set(
            'admin.crud.modal', 'modal/{config}/{action}', [
                'controller' => '\\Admin\\Controller\\CrudController',
                'action'     => 'index',
                'modal'      => true
            ]
        )->setPriority(-200);

        //$routes->addPrefix('admin/');
    }

    public function initServices(\Application $app)
    {
        $app['admin.url_prefix'] = function() {
            return '';
        };

        $app['admin.ui_manager'] = function() {
            return new \Admin\Ui\UiManager();
        };
    }

    function init(\Application $app, Request $req, RouteCollection $routes)
    {
        \W5n\Model\Field\Image::setPrefixUri($req->getUriForPath('/assets/uploads/images/'));
        \W5n\Model\Field\Image::setPrefixPath(ASSETS_PATH . 'uploads/images');
        \W5n\Model\Field\Upload::setPrefixUri($req->getUriForPath('/assets/uploads/files/'));
        \W5n\Model\Field\Upload::setPrefixPath(ASSETS_PATH . 'uploads/files');
    }
}
