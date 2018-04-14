<?php
namespace Frontend\Controller;

use Symfony\Component\HttpFoundation\Request;
use Application;

class ListDetailController extends FrontendController
{
    private $config         = null;
    private $frontendConfig = null;

    public function __construct(Application $app, Request $request)
    {
        parent::__construct($app, $request);
        $config = $request->attributes->get('config');

        if (empty($config)) {
            throw new \LogicException("'config' parameter must be set in route.");
        }

        $this->config = $app->loadConfig($config);

        if (empty($this->config)) {
            throw new \LogicException("Invalid config '$config'.");
        }

        $this->frontendConfig = isset($this->config['frontend'])
                              ? $this->config['frontend']
                              : [];

        $this->layout = $this->getFrontendConfig('layout', 'layouts/site');
    }

    public function indexAction()
    {
        $page         = $this->getRequest()->query->get('page', 1);
        $totalPages   = 0;
        $items        = $this->fetchItems(
            $page,
            $totalPages,
            $this->getFrontendConfig('items_per_page', 10)
        );


        $view = $this->createView(
            $this->getFrontendConfig('list_view', 'frontend/default-list'),
            [
                'items'        => $items,
                'page'         => $page,
                'totalPages'   => $totalPages,
                'config'       => $this->config,
                'emptyMessage' => $this->getFrontendConfig(
                    'list_empty_message',
                    'Nenhum item'
                ),
                'options'      => $this->getFrontendConfig('options', []),
                'detailRoute'  => $this->getFrontendConfig('detail_route'),
                'listRoute'    => $this->getFrontendConfig('list_route')
            ]
        );

        $callback = $this->getFrontendConfig('on_list');

        if (!empty($callback)) {
            call_user_func($callback, $this->getRequest(), $view);
        }

        return $view;
    }

    public function modifyView(\W5n\Templating\View $view)
    {
        parent::modifyView($view);
        $view->request = $this->getRequest();
    }

    public function detailAction()
    {
        $item = $this->fetchItem();
        if (empty($item)) {
            $this->addWarningFlash(
                $this->getFrontendConfig('item_not_found_message')
            );
            return $this->routeRedirect($this->getFrontendConfig('list_route'));
        }

        $view = $this->createView(
            $this->getFrontendConfig('detail_view'),
            [
                'item'        => $item,
                'config'       => $this->config,
                'options'      => $this->getFrontendConfig('options', []),
                'detailRoute'  => $this->getFrontendConfig('detail_route'),
                'listRoute'    => $this->getFrontendConfig('list_route')
            ]
        );

        $callback = $this->getFrontendConfig('on_detail');

        if (!empty($callback)) {
            call_user_func($callback, $this->getRequest(), $view);
        }

        return $view;
    }

    private function getConfig($key, $default = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    private function getFrontendConfig($key, $default = null)
    {
        return isset($this->frontendConfig[$key]) ? $this->frontendConfig[$key] : $default;
    }

    private function getConfigOrThrow($key)
    {
        if (!isset($this->config[$key])) {
            throw new \LogicException("Config key not found: '$key'.");
        }

        return $this->getConfig($key);
    }

    private function fetchItems($page, &$totalPages = null, $perPage = 10)
    {
        $modelClass     = $this->getConfigOrThrow('model');
        $frontendConfig = $this->frontendConfig;

        $modifyQueryCallback = $this->getFrontendConfig(
            'modify_list_query',
            $this->getFrontendConfig('modify_query', null)
        );

        $order = isset($frontendConfig['order_by'])
               ? $frontendConfig['order_by']
               : null;

        $direction = isset($frontendConfig['order_by_direction'])
                   ? $frontendConfig['order_by_direction']
                   : null;
        $totalRows = null;

        if (!empty($modifyQueryCallback)) {
            $request = $this->getRequest();
            $modifyQueryCallback = function ($query) use ($modifyQueryCallback, $request) {
                return call_user_func($modifyQueryCallback, $query, $request);
            };
        }

        return call_user_func(
            [$modelClass, 'inflateAll'],
            call_user_func_array( //array because of the usage of references
                [$modelClass, 'findAllPaginated'],
                [
                    $page,
                    $perPage,
                    &$totalPages,
                    &$totalRows,
                    [], //conditions
                    $modifyQueryCallback,
                    $order,
                    $direction
                ]
            )
        );
    }

    private function fetchItem()
    {
        $modelClass          = $this->getConfigOrThrow('model');
        $modifyQueryCallback = $this->getFrontendConfig(
            'modify_detail_query',
            $this->getFrontendConfig('modify_query', null)
        );

        $fields = $this->getFrontendConfig('detail_find_fields', ['id']);

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        $conditions = [];

        foreach ($fields as $field) {
            $parts       = explode('.', $field);
            $placeholder = $field;

            if (count($parts) > 1) {
                $placeholder = $parts[1];
            }

            $value = $this->getRequest()->attributes->get($placeholder, null);
            $conditions[$field. '=:' . $placeholder] = [$placeholder => $value];
        }

        if (!empty($modifyQueryCallback)) {
            $request = $this->getRequest();
            $modifyQueryCallback = function ($query) use ($modifyQueryCallback, $request) {
                return call_user_func($modifyQueryCallback, $query, $request);
            };
        }

        return call_user_func(
            [$modelClass, 'find'],
            $conditions,
            $modifyQueryCallback
        );

    }
}

