<?php
namespace Usuarios;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class UsuariosModule extends \W5n\Module
{
    function initRoutes(RouteCollection $routes)
    {
        $routes->set(
            'admin.usuarios.editar_perfil',
            'admin/editar-perfil',
            [
                'controller' => '\\Usuarios\\Controller\\Admin\\UsuariosController',
                'action'     => 'editProfile'
            ]
        );

        $routes->set(
            'admin.usuarios.alterar_senha',
            'admin/alterar-senha',
            [
                'controller' => '\\Usuarios\\Controller\\Admin\\UsuariosController',
                'action'     => 'changePassword'
            ]
        );
    }

    function initServices(\Application $app)
    {
    }

    function init(\Application $app, Request $req, RouteCollection $routes)
    {
        $ui = $app['admin.ui_manager'];
        // $ui->ensureMenu('others', 'Outros', 'ellipsis-h', null, [], INF);
        $ui->addMenu('usuarios', 'Usuários', 'user', $app->routeUrl('admin.crud', ['config' => 'usuarios']), null, 100000);
        $ui->addUserAction('edit-profile', 'Editar Perfil', $app->routeUrl('admin.usuarios.editar_perfil'));
        $ui->addUserAction('edit-password', 'Alterar Senha', $app->routeUrl('admin.usuarios.alterar_senha'));
        $ui->addUserAction('logout', 'Sair', $app->routeUrl('admin.auth', ['action' => 'logout']));
    }
}
