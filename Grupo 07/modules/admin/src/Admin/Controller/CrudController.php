<?php
namespace Admin\Controller;

use \Doctrine\DBAL\Query\QueryBuilder;
use \W5n\Model\Model;
use W5n\Table;
use W5n\Model\Form;

class CrudController extends AdminController
{

    protected $config          = array();
    protected $layout          = 'layout/admin';
    protected $bulkActions     = array();
    protected $rowActions      = array();
    protected $appConfig       = array();

    public function isAllowed($action, $module = null)
    {
        //TODO: check user credentials
        return true;
        if (is_null($module)) {
            $module = $this->getConfigKey();
        }
        return User::isAllowed($module, $action);
    }

    public function checkAction($action, &$response = null)
    {
        //TODO: check if user has permission
        return true;
    }


    public function getRouteUrl($route, array $params = array())
    {
        if (!isset($params['config'])) {
            $params['config'] = $this->getConfigKey();
        }
        return \W5n\Routing\Router::generate($this->getRequest(), $route, $params);
    }

    protected function getAppConfig($key = null, $default = null)
    {
        if (empty($this->appConfig)) {
            $this->appConfig = $this->getApplication()->loadConfig('app');
        }
        if (empty($key)) {
            return $this->appConfig;
        }

        return isset($this->appConfig[$key]) ? $this->appConfig[$key] : $default;
    }


    protected function getConfig($key = null, $defaultValue = null, $configKey = null)
    {
        $configKey = $this->getConfigKey($configKey);

        if (empty($configKey)) {
            throw new \LogicException('"config" key must be set in Request.');
        }


        if (empty($this->config[$configKey])) {
            $app = $this->getApplication();
            $this->config[$configKey] = $app->loadConfig($configKey);

            if (isset($this->config[$configKey]['after_load_config'])
                && is_callable($this->config[$configKey]['after_load_config'])
            ) {
                call_user_func($this->config[$configKey]['after_load_config'], $this);
            }
        }

        if (empty($this->config[$configKey])) {
            throw new \LogicException(sprintf('Config "%s" not found.', $configKey));
        }

        if (empty($key)) {
            return isset($this->config[$configKey]) ? $this->config[$configKey] : $defaultValue;
        }

        return isset($this->config[$configKey][$key]) ? $this->config[$configKey][$key] : $defaultValue;
    }

    protected function getConfigKey($configKey = null)
    {
        if (!empty($configKey)) {
            return $configKey;
        }

        return $this->getRequest()->attributes->get(
            'config_file',
            $this->getRequest()->attributes->get('config')
        );
    }

    public function init()
    {
        parent::init();

        $app = $this->getApplication();
        $this->getUiManager()->addCrumb(
            $this->getAppConfig('name'),
            $this->getRequest()->getUriForPath('/')
        );
        $this->getUiManager()->addCrumb(
            $this->getConfig('plural'),
            $this->getRouteUrl($this->getConfig('list_route', $this->getDefaultRouteName()))
        );
        $this->getUiManager()->setPageTitle($this->getConfig('plural'));
        $this->getUiManager()->setActiveMenu($this->getActiveMenu(), $this->getActiveSubmenu());

        $init = $this->getConfig('init');
        if (\is_callable($init)) {
            \call_user_func($init, $this);
        }
    }

    public function getActiveMenu()
    {
        return $this->getConfig(
            'active_menu',
            $this->getRequest()->attributes->get('config')
        );
    }

    public function getActiveSubmenu()
    {
        return $this->getConfig(
            'active_submenu'
        );
    }

    public function preIndex()
    {
        if ($this->isAllowed('insert') && $this->getConfig('can_add', true)) {
            $this->getUiManager()->addPageAction(
                'insert',
                'Adicionar',
                $this->getRouteUrl(
                    $this->getConfig('insert_route', $this->getDefaultRouteName()),
                    array(
                        'action' => 'insert'
                    )
                ),
                'plus',
                null,
                true,
                ['class' => 'btn-success']
            );
        }

        if ($this->isExportActionEnabled()) {
            $this->getUiManager()->addPageAction(
                'export',
                'Exportar',
                $this->getRouteUrl(
                    $this->getConfig('export_route', $this->getDefaultRouteName()),
                    array(
                        'action' => 'export'
                    )
                ),
                'file-excel-o',
                null,
                true,
                ['class' => 'btn-warning']
            );
        }
    }

    public function exportAction()
    {
        if (!$this->checkAction('export', $r)) {
            return $r;
        }

        $this->preExport();

        $config   = $this->getConfig('export');
        $callback = isset($config['modify']) ? $config['modify'] : null;
        $data     = $this->getAllData('*', $callback);



        if (empty($data)) {
            $this->addWarningFlash($this->getEmptyMessage());
            return $this->redirectToList();
        }

        $excel = new \PHPExcel();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle($this->getConfig('plural'));

        $columns = $config['columns'];
        $filters = isset($config['filters']) ? $config['filters'] : array();
        $colIdx = 0;

        foreach ($columns as $field => $label) {
            $cell = $sheet->setCellValueByColumnAndRow($colIdx++, 1, $label, true);
            $cell->getStyle()->applyFromArray(array('font' => array('name' => 'Arial', 'bold' => true, 'size' => 13)));
        }

        $rowIdx = 2;
        $colIdx = 0;
        foreach ($data as $row) {
            foreach ($columns as $field => $label) {
                if (is_numeric($field)) {
                    $field = $label;
                }
                $value = isset($row[$field]) ? $row[$field] : '';
                if (isset($filters[$field]) && is_callable($filters[$field])) {
                    $value = call_user_func($filters[$field], $value, $row, $rowIdx, $colIdx);
                }
                $sheet->setCellValueByColumnAndRow($colIdx++, $rowIdx, $value);
            }
            $colIdx = 0;
            $rowIdx++;
        }

        $sheet->calculateColumnWidths();

        ob_start();
        $writer = new \PHPExcel_Writer_Excel2007($excel);
        $writer->save('php://output');

        $response = new \Symfony\Component\HttpFoundation\Response(
            ob_get_clean()
        );



        $d = $response->headers->makeDisposition(
            \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->getConfig('plural') . '.xls'
        );

        $response->headers->set('Content-Disposition', $d);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');

        return $response;
    }

    public function preExport()
    {

    }

    public function preInsert()
    {
        if ($this->getConfig('add_backbutton', true)) {
            $this->getUiManager()->addPageAction(
                'back',
                'Voltar',
                $this->getRouteUrl(
                    $this->getListRoute()
                ),
                'chevron-circle-left'
            );
        }
    }

    public function preUpdate()
    {
        $this->getUiManager()->addPageAction(
            'back',
            'Voltar',
            $this->getRouteUrl(
                $this->getListRoute()
            ),
            'chevron-circle-left'
        );
    }


    public function indexAction()
    {
        if (!$this->checkAction('index', $r)) {
            return $r;
        }

        $this->preIndex();
        $ui = $this->getUiManager();
        $ui->addCrumb('Listagem');
        $ui->setPageDescription('Listagem');


        $itemsPerPage = $this->getItemsPerPage();
        $currentPage  = $this->getRequest()->get('page', 1);
        $data         = $this->getData($currentPage, $itemsPerPage, $totalRows, $totalPages);

        if (empty($data)) {
            $this->addWarningFlash($this->getEmptyMessage());
        }

        $table = new Table($data);
        $this->stylizeTable($table);
        $this->setTableHeaders($table);
        $this->setTableFilters($table);
        $this->setRowActions();
        $this->applyRowActions($table);
        $this->setTableHeaders($table);
        $this->setTableFilters($table);
        $this->modifyTable($table);

        $tableConfig = $this->getConfig('table');
        if (isset($tableConfig['after_setup']) && is_callable($tableConfig['after_setup'])) {
            call_user_func($tableConfig['after_setup'], $table);
        }

        $view = $this->createView();
        $view->itemsPerPage    = $itemsPerPage;
        $view->currentPage     = $currentPage;
        $view->totalPages      = $totalPages;
        $view->firstRow        = (($currentPage - 1) * $itemsPerPage) + 1;
        $view->lastRow         = ($view->firstRow - 1) + count($data);
        $view->totalRows       = $totalRows;
        $view->table           = $table;
        $view->hasData         = !empty($data);
        $view->paginationRoute = $this->getConfig('list_route', $this->getDefaultRouteName());
        $view->configKey       = $this->getConfigKey();
        $view->search          = $this->getConfig('search');
        $searchView            = $this->getConfig('search_view');


        if (!empty($searchView)) {
            $view->searchView = $searchView;
        }

        if (!empty($view->search)) {
            $view->searchModel = $this->createSearchModel();
        }

        return $view->render($this->getConfig('list_view', 'admin/default_listing'));
    }

    protected function isExportActionEnabled()
    {
        if (!$this->isAllowed('export') && $this->getConfig('can_export', true)) {
            return false;
        }
        $exportInfo = $this->getConfig('export');
        return !empty($exportInfo);
    }

    protected function getAllData($columns = '*', $modifyQuery = null)
    {
        /*@var $builder \Doctrine\DBAL\Query\QueryBuilder*/
        $builder = call_user_func(array($this->getConfig('model'), 'getFindQueryBuilder'));
        $builder->select($columns);
        $this->applyListQueryFilters($builder, false);
        if (is_callable($modifyQuery)) {
            call_user_func($modifyQuery, $builder);
        }

        return $builder->execute()->fetchAll();
    }

    public function createView($file = null, array $data = array())
    {
        $view       = parent::createView($file, $data);
        $modifyView = $this->getConfig('modify_view');
        if (!empty($modifyView) && is_callable($modifyView)) {
            call_user_func($modifyView, $view);
        }
        return $view;
    }

    public function modifyView(\W5n\Templating\View $view)
    {
        parent::modifyView($view);
        $view->request = $this->getRequest();
        $view->config  = $this->getConfig();

        $isModal = $this->getRequest()->attributes->get('modal', false);
        if ($isModal) {
            $view->extend('layout/clean');
        }
    }

    protected function getData($page, $perPage, &$totalRows = null, &$totalPages = null)
    {
        $table = $this->getConfig('table');
        $fields = isset($table['fields']) ? $table['fields'] : array();

        /*@var $builder \Doctrine\DBAL\Query\QueryBuilder*/
        $builder = call_user_func(array($this->getConfig('model'), 'getFindQueryBuilder'));
        $builder->select('COUNT(*) as total');
        $this->modifyListQuery($builder, true);
        $this->applyListQueryFilters($builder, true);

        if (isset($table['before_execute']) && is_callable($table['before_execute'])) {
            call_user_func($table['before_execute'], $builder, true);
        }

        $result = $builder->execute()->fetch();

        $totalRows  = $result['total'];
        $totalPages = ceil($totalRows / $perPage);
        if ($page <= 0 || $page > $totalPages) {
            return array();
        }

        $builder = call_user_func(array($this->getConfig('model'), 'getFindQueryBuilder'));

        $builder->select(array_keys($fields));

        $builder->setFirstResult(($page - 1) * $perPage);
        $builder->setMaxResults($perPage);
        if (isset($table['order_by'])) {
            if (is_string($table['order_by'])) {
                $builder->orderBy($table['order_by']);
            } else if (is_array($table['order_by'])) {
                $orderBy   = $table['order_by'][0];
                $direction = isset($table['order_by'][1]) ? $table['order_by'][1] : null;
                $builder->orderBy($orderBy, $direction);
            }


        }


        $this->modifyListQuery($builder, false);


        $this->applyListQueryFilters($builder, false);

        if (isset($table['before_execute']) && is_callable($table['before_execute'])) {
            call_user_func($table['before_execute'], $builder, false);
        }

        return $builder->execute()->fetchAll();
    }

    protected function modifyListQuery(QueryBuilder $builder, $isCountQuery = false) {}




    protected function getItemsPerPage()
    {
        $table = $this->getConfig('table');

        return isset($table['per_page']) ? $table['per_page'] : 15;
    }

    protected function putDefaultVariablesOnView(\View $view)
    {
        $view->application   = $this->getApplication();
        $view->ui            = $this->getUiManager();
        $view->request       = $this->getRequest();
        $view->config        = $this->getConfig();
    }

    protected function createSearchModel()
    {
        $config = $this->getConfig('search');
        if (empty($config)) {
            return null;
        }

        $model      = new Model();
        $searchData = $this->getRequest()->query->get('search', array());
        foreach ($config as $name => $info) {
            switch ($info['type']) {
                case 'boolean':
                    $model->boolean($name, $info['label']);
                    break;
                case 'date':
                    $model->date($name, $info['label']);
                    break;
                case 'money':
                    $model->money($name, $info['label']);
                    break;
                case 'integer':
                    $model->integer($name, $info['label']);
                    break;
                case 'db_options':

                    //$model->date($name, $info->label);
                    $options = $info['options'];

                    $model->dbOptions(
                        $name,
                        $info['label'],
                        strval($options['table']),
                        strval($options['value']),
                        strval($options['label']),
                        '- ' . $info['label'] . ' -', null, (isset($options['modify']) ? $options['modify'] : null)
                    );
                    break;
                case 'db_multioptions':

                    $options = $info['options'];

                    $model->habtm(
                        $name,
                        $info['label'],
                        strval($options['table']),
                        strval($options['this_fk']),
                        strval($options['other_fk']),
                        strval($options['display_field'])
                    )->setOption('multiple', true);
                    break;
                case 'options':
                    $options = $info['options']['options'];
                    if (is_callable($options)) {
                        $options = call_user_func($options);
                    }
                    if (empty($options)) {
                        continue;
                    }
                    $model->options($name, $info['label'], (array)$options, '- ' . $info['label'] . ' -');
                    break;
                case 'text':
                    $model->text($name, $info['label']);
                    break;
                case 'money':
                    $model->money($name, $info['label']);
                    break;
                case 'integer':
                    $model->integer($name, $info['label']);
                    break;
                case 'ajax_options':
                    $options = array();
                    if (!empty($info['options']['depends']))  {
                        $dependsField = $info['options']['depends'];
                        if (!empty($searchData[$dependsField])) {
                            $result = MiscController::getSearchAjaxOptions(
                                $searchData[$dependsField],
                                $this->getConfigKey(),
                                $name
                            );
                            $options = array();
                            foreach ($result as $r) {
                                $options[$r['value']] = $r['label'];
                            }


                        }
                    }
                    $field = $model->options($name, $info['label'], $options, '- ' . $info['label'] . ' -');
                    if (!empty($options)) {
                        $field->setOption('options', $options);
                    }
                    if (!empty($searchData[$name])) {
                        $model->getField($name)->setValue($searchData[$name]);
                    }
                    break;
            }
            if (!empty($info['modify']) && is_callable($info['modify'])) {
                call_user_func($info['modify'], $model->getField($name));
            }

        }
        $modifySearchModelCallback = $this->getConfig('modify_search_model');
        if (!empty($modifySearchModelCallback) && is_callable($modifySearchModelCallback)) {
            call_user_func($modifySearchModelCallback, $model);
        }
        $this->modifySearchModel($model);

        $model->populateFromArray($searchData);
        $model->validate();

        return $model;
    }

    protected function modifySearchModel(Model $model) {}

    protected function instantiateModel()
    {
        $modelClass = $this->getConfig('model');

        if (empty($modelClass)) {
            throw new \LogicException('"model" must be set in config file.');
        }

        $class = new \ReflectionClass($modelClass);

        $instance = $class->newInstance($this->getApplication());
        $this->triggerAfterCreateModel($instance);
        return $instance;
    }

    protected function triggerAfterCreateModel(Model $model)
    {
        $afterCreateModel = $this->getConfig('after_create_model');
        if (!empty($afterCreateModel) && is_callable($afterCreateModel)) {
            call_user_func($afterCreateModel, $model);
        }
    }

    public function insertAction()
    {
        $canInsert = $this->getConfig('can_add', true);
        if (!$this->isAllowed('insert') || !$canInsert) {
            $this->addInfoFlash($this->getActionNotEnabledMessage());
            return $this->redirectToList();
        }

        $this->preInsert();
        $view = $this->createView();
        if ($this->getConfig('add_subtitle', true)) {
            $this->ui->setPageDescription('Adicionar ' . $this->getConfig('singular'));
        }
        if ($this->getConfig('add_breadcrumb', true)) {
            $this->ui->addCrumb('Adicionar ' . $this->getConfig('singular'));
        }

        $model = $this->instantiateModel();
        $form  = new Form($model, $this->getRequest()->getUri());
        $form->addClass('validation');

        if ($form->isSubmitted($this->getRequest())) {
            $model->populateFromArray($this->getRequest()->request->all());
            if ($model->save()) {
                $this->addSuccessFlash($this->getInsertSuccessMessage());
                return $this->redirect($this->getAfterInsertRedirectUrl($model));
            } else {
                $this->addErrorFlash($this->getNotValidatedFormMessage());
            }
        }

        $view->form       = $form;
        $view->model      = $model;
        $view->formLayout = $this->getFormLayout($model, $form);
        $view->listRoute  = $this->getConfig('list_route', $this->getDefaultRouteName());
        $view->configKey  = $this->getConfigKey();
        $view->backButton = $this->getConfig('add_backbutton', true);
        $formView   = $this->getConfig('form_view', 'admin/default_form');

        return $view->render($formView);
    }

    protected function getAfterInsertRedirectUrl(Model $model)
    {
        return $this->getRouteUrl(
            $this->getConfig(
                'after_insert_route',
                $this->getDefaultRouteName()
            ),
            array(
                'action' => 'insert'
            ));
    }

    protected function getAfterUpdateRedirectUrl(Model $model)
    {
        $route = $this->getConfig(
            'after_update_route',
            $this->getDefaultRouteName()
        );

        if ($route === false) {
            return $this->getRouteUrl($this->getListRoute());
        }

        return $this->getRouteUrl(
            $route,
            array(
                'action' => 'update'
            )
        ) . '?id=' . $model->id();
    }

    protected function getInsertSuccessMessage()
    {
        $message = $this->getConfig('insert_success_message');

        if (empty($message)) {
            $template = '%s adicionad%s com sucesso.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? 'o' : 'a';
            $message = sprintf($template, $this->getConfig('singular'), $article);
        }
        return $message;
    }

    protected function getUpdateSuccessMessage()
    {
        $message = $this->getConfig('update_success_message');

        if (empty($message)) {
            $template = '%s atualizad%s com sucesso.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? 'o' : 'a';
            $message = sprintf($template, $this->getConfig('singular'), $article);
        }
        return $message;
    }

    protected function getDeleteSuccessMessage()
    {
        $message = $this->getConfig('delete_success_message');

        if (empty($message)) {
            $template = '%s excluíd%s com sucesso.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? 'o' : 'a';
            $message = sprintf($template, $this->getConfig('singular'), $article);
        }
        return $message;
    }

    protected function getDeleteErrorMessage()
    {
        $message = $this->getConfig('delete_error_message');

        if (empty($message)) {
            $template = 'Não foi possível excluir %s %s.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? 'o' : 'a';
            $message = sprintf($template, $article, strtolower($this->getConfig('singular')));
        }
        return $message;
    }


    protected function getEmptyMessage()
    {
        $message = $this->getConfig('empty_message');

        if (empty($message)) {
            $template = 'Nenhum%s %s.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? '' : 'a';
            $message = sprintf($template, $article, strtolower($this->getConfig('singular')));
        }
        return $message;
    }


    protected function getNotFoundMessage()
    {
        $message = $this->getConfig('not_found_message');

        if (empty($message)) {
            $template = '%s não encontrad%s.';
            $article  = $this->getConfig('gender', 'm') != 'f' ? 'o' : 'a';
            $message = sprintf($template, $this->getConfig('singular'), $article);
        }
        return $message;
    }

    protected function getActionNotEnabledMessage()
    {
        $message = $this->getConfig('action_not_enabled_message');

        if (empty($message)) {
            $message = 'Esta ação não está habilitada.';
        }
        return $message;
    }

    public function deleteAction()
    {
        $canDelete = $this->getConfig('can_delete', true);
        if (!$this->isAllowed('delete') || !$canDelete) {
            $this->addInfoFlash($this->getActionNotEnabledMessage());
            return $this->redirectToList();
        }

        $id    = $this->getRequest()->query->get('id');
        $model = call_user_func(array($this->getConfig('model'), 'findById'), $id);

        if (empty($model)) {
            $this->addErrorFlash($this->getNotFoundMessage());
            return $this->redirectToList();
        }

        $this->triggerAfterCreateModel($model);

        try {
            if($model->delete()) {
                $this->addSuccessFlash($this->getDeleteSuccessMessage());
            } else {
                $this->addErrorFlash($this->getDeleteErrorMessage());
            }
        } catch (\Exception $ex) {
            $this->addErrorFlash('Este registro não pode ser excluído pois possui outros registros relacionados.');
        }

        return $this->redirect(
            $this->getRouteUrl(
                $this->getConfig('list_route', $this->getDefaultRouteName())
            )
        );

        return $this->redirectToList();
    }

    protected function redirectToList()
    {
        return $this->redirect(
            $this->getRouteUrl(
                $this->getListRoute()
            )
        );
    }


    protected function getListRoute()
    {
        return $this->getConfig('list_route', $this->getDefaultRouteName());
    }

    public function getFormLayout(Model $model, Form $form)
    {
       $formLayout = $this->getConfig('form_layout', array());
       if (is_callable($formLayout)) {
           $formBuilder = new \W5n\Html\FormBuilder();
           call_user_func($formLayout, $formBuilder, $model, $form);
           $formLayout = $formBuilder->getLayout();
       }
       return $formLayout;
    }

    public function updateAction()
    {
        $canUpdate = $this->getConfig('can_edit', true);
        if (!$this->isAllowed('update') || !$canUpdate) {
            $this->addInfoFlash($this->getActionNotEnabledMessage());
            return $this->redirectToList();
        }

        $this->preUpdate();
        $view = $this->createView();
        $this->ui->setPageDescription('Editar ' . $this->getConfig('singular'));
        $this->ui->addCrumb('Editar ' . $this->getConfig('singular'));

        $id    = $this->getRequest()->query->get('id');
        $model = call_user_func(array($this->getConfig('model'), 'findById'), $id);

        if (empty($model)) {
            $this->addErrorFlash($this->getNotFoundMessage());
            return $this->redirectToList();
        }
        $this->triggerAfterCreateModel($model);

        $form  = new Form($model, $this->getRequest()->getUri());
        $form->addClass('validation');

        if ($form->isSubmitted($this->getRequest())) {
            $model->populateFromArray($this->getRequest()->request->all());
            if ($model->save()) {
                $this->addSuccessFlash($this->getUpdateSuccessMessage());
                return $this->redirect($this->getAfterUpdateRedirectUrl($model));
            } else {
                $this->addErrorFlash($this->getNotValidatedFormMessage());
            }
        }

        $view->form       = $form;
        $view->model      = $model;
        $view->formLayout = $this->getFormLayout($model, $form);
        $view->listRoute  = $this->getConfig('list_route', $this->getDefaultRouteName());
        $view->configKey  = $this->getConfigKey();
        $controller       = $this;
        $formView   = $this->getConfig('form_view', 'admin/default_form');

        return $view->render($formView);
    }


    public function viewAction()
    {
        $canView = $this->getConfig('can_view', false);
        if (!$this->isAllowed('view') || !$canView) {
            $this->addInfoFlash($this->getActionNotEnabledMessage());
            return $this->redirectToList();
        }

        $viewConfig = $this->getConfig('view');

        $view = $this->createView();
        $this->ui->setPageDescription('Visualizar ' . $this->getConfig('singular'));
        $this->ui->addCrumb('Visualizar ' . $this->getConfig('singular'));

        $id    = $this->getRequest()->query->get('id');
        $model = call_user_func(array($this->getConfig('model'), 'findById'), $id);

        if (empty($model)) {
            $this->addErrorFlash($this->getNotFoundMessage());
            return $this->redirectToList();
        }
        $this->triggerAfterCreateModel($model);

        $view->hasPrint   = !empty($viewConfig['print_view']);
        $view->model      = $model;
        $view->layout     = isset($viewConfig['layout']) ? $viewConfig['layout'] : null;
        $view->listRoute  = $this->getConfig('list_route', $this->getDefaultRouteName());
        $view->configKey  = $this->getConfigKey();
        $view->viewConfig = $viewConfig;
        $view->form       = new Form($view->model, '');
        $formView         = isset($viewConfig['view_file']) ? $viewConfig['view_file'] : 'admin/default_view';

        return $view->render($formView);
    }

    public function printAction()
    {

        $canPrint = $this->getConfig('can_print', false);
        if (!$this->isAllowed('print') || !$canPrint) {
            $this->addInfoFlash($this->getActionNotEnabledMessage());
            return $this->redirectToList();
        }

        $printView   = $this->getConfig('print_view');
        $printLayout = $this->getConfig('print_layout');

        $id    = $this->getRequest()->query->get('id');
        $model = call_user_func(array($this->getConfig('model'), 'findById'), $id);

        if (empty($model)) {
            $this->addErrorFlash($this->getNotFoundMessage());
            return $this->redirectToList();
        }
        $this->triggerAfterCreateModel($model);
        $view             = $this->createView();
        $view->model      = $model;
        $view->configKey  = $this->getConfigKey();
        $view->form       = new Form($view->model, '');
        $view->layout     = $printLayout;
        $view->extend('admin/print_layout');
        return $view->render($printView);
    }


    protected function applyRowActions(Table $table)
    {
        if (!empty($this->rowActions)) {
            $table->addColumn('actions', '', '-', INF);
            $table->setHeaderAttribute('actions', 'class', 'actions');
            $table->setHeaderAttribute('actions', 'style', 'width:' . count($this->rowActions) * 35 . 'px');
            $rowActions = $this->getRowActions();
            $view       = $this->createView()->extend(null);

            $view->rowActions = $rowActions;
            $table->setColumnTemplate(
                'actions',
                function ($data) use ($view) {
                    return $view->render('admin/row_actions', ['row' => $data]);
                }
            );
        }

    }

    protected function getRowActions()
    {
        uasort($this->rowActions, function ($a, $b){
            if ($a['weight'] < $b['weight']) {
                return -1;
            }
            return 1;
        });
        $actions = $this->rowActions;
        foreach ($actions as &$a) {
            $attrStr = '';
            if (!empty($a['attrs'])) {
                foreach ($a['attrs'] as $attr => $value) {
                    $attrStr .= sprintf(' %s="%s"', $attr, htmlentities($value, ENT_QUOTES));
                }
            }
            $a['attrsStr'] = $attrStr;
        }

        return $actions;
    }


    protected function setRowActions()
    {
        $id = $this->getConfig('primaryKey', 'id');

        if ($this->isAllowed('view') && $this->getConfig('can_view', false)) {
            $this->addRowAction(
                'view',
                $this->getRouteUrl($this->getDefaultRouteName(), array('action' => 'view')) . '?id={' . $id . '}',
                'Visualizar',
                'eye'
            );
        }

        if ($this->isAllowed('print') && $this->getConfig('can_print', false)) {
            $this->addRowAction(
                'print',
                $this->getRouteUrl($this->getDefaultRouteName(), array('action' => 'print')) . '?id={' . $id . '}',
                'Imprimir',
                'print',
                array('target' => '_blank')
            );
        }

        if ($this->isAllowed('update') && $this->getConfig('can_edit', true)) {
            $this->addRowAction(
                'edit',
                $this->getRouteUrl($this->getDefaultRouteName(), array('action' => 'update')) . '?id={' . $id . '}',
                'Editar',
                'pencil',
                array(),
                -1
            );
        }

        if ($this->isAllowed('delete') && $this->getConfig('can_delete', true)) {
            $this->addRowAction(
                'delete',
                $this->getRouteUrl($this->getDefaultRouteName(), array('action' => 'delete')) . '?id={' . $id . '}',
                'Excluir',
                'trash-o',
                array(
                    'class'             => 'modal-confirm',
                    'data-confirm-type' => 'error',
                    'data-confirm'      => 'Você tem certeza que deseja excluir esse registro permanentemente?'
                )
            );
        }
    }


    public function addRowAction($id, $url, $title, $icon = null, $attrs = array(), $weight = 0)
    {
        $attrs['class']    = 'tooltips';
        $attrs['data-rel'] = 'tooltip';
        $attrs['data-trigger'] = 'hover';
        $attrs['data-placement'] = 'top';

        $this->rowActions[$id] = array(
            'url'    => $url,
            'title'  => $title,
            'icon'   => $icon,
            'attrs'  => $attrs,
            'weight' => $weight
        );
        return $this;
    }

    public function removeRowAction($id)
    {
        unset($this->rowActions[$id]);
        return $this;
    }

    public function stylizeTable(Table $table)
    {
        $table->setTableAttribute('class', 'table table-striped table-bordered admin-list table-hover');
    }

    public function modifyTable(Table $table) {}

    public function setTableHeaders(Table $table)
    {
        $tableConfig = $this->getConfig('table', array());
        $headers     = isset($tableConfig['labels']) ? $tableConfig['labels'] : array();
        $columns     = isset($tableConfig['fields']) ? $tableConfig['fields'] : array();
        $hidden      = isset($tableConfig['hidden']) ? $tableConfig['hidden'] : array();

        foreach ($columns as $field => $label) {
            if ($label === FALSE) {
                $table->hideColumn($field);
                continue;
            }
            if (isset($headers[$field])) {
                $label = $headers[$field];
            }
            $table->setHeader($field, $label);
        }

        foreach ($headers as $field => $label) {
            $table->setHeader($field, $label);
        }

        foreach ($hidden as $h) {
            $table->hideColumn($h);
        }
    }

    public function setTableFilters(Table $table)
    {
        $tableConfig = $this->getConfig('table', array());

        if (!is_array($tableConfig))
            $tableConfig = $tableConfig->toArray();

        $filters = isset($tableConfig['filters']) ? $tableConfig['filters'] : array();

        foreach ($filters as $field => $filter) {
            $table->setFilter($field, $filter);
        }
    }

    public function applyListQueryFilters(\Doctrine\DBAL\Query\QueryBuilder $select, $isCountQuery = false)
    {
        $searchModel = $this->createSearchModel();

        if (empty($searchModel) || $searchModel->hasErrors()) {
            return;
        }

        $search = $this->getConfig('search');

        foreach ($search as $name => $info) {
            if (empty($info['fields'])) {
                continue;
            }
            $value = $searchModel
                ->getField($name)
                ->getValue(\W5n\Model\Model::OP_INSERT);

            if ((is_array($value))) {
                if (empty($value)) {
                    continue;
                }
            } else if (strlen($value) == 0 || ($info['type'] == 'boolean' && !$value)) {
                continue;
            }

            if (!empty($info['valueFilter'])) {
                $value = call_user_func($info->valueFilter, $value, $isCountQuery);
                if (strlen($value) == 0) {
                    continue;
                }
            }

            $exprs = array();

            foreach ($info['fields'] as $field => $type) {
                if (is_callable($type)) {
                    call_user_func($type, $select, $value);
                    continue;
                }

                if ($type == 'exact') {
                    $sign = '=';
                } elseif ($type == 'gt' || $type == 'date_gt') {
                    $sign = '>';
                    if ($type == 'date_gt') {
                        $value .= ' 00:00:00';
                    }
                } elseif ($type == 'gte' || $type == 'date_gte') {
                    $sign = '>=';
                    if ($type == 'date_gte') {
                        $value .= ' 00:00:00';
                    }
                } elseif ($type == 'lt' || $type == 'date_lt') {
                    $sign = '<';
                    if ($type == 'date_lt') {
                        $value .= ' 23:59:59';
                    }
                } elseif ($type == 'lte' || $type == 'date_lte') {
                    $sign = '<=';
                    if ($type == 'date_lte') {
                        $value .= ' 23:59:59';
                    }
                } elseif ($type == 'like') {
                    $sign  = ' ~* ';
                } elseif ($type == 'ilike') {
                    $sign  = ' ~* ';
                } elseif ($type == 'empty') {
                    $exprs[] = 'IF ((' . $field . ' IS NOT NULL OR ' . $field . "=''), 1, 0) = " . $select->createNamedParameter($value);
                    continue;
                } else {
                    continue;
                }

                if (is_array($value)) {
                    $conn  = $this->getConnection();
                    $value = array_map(function ($v) use ($conn) {

                        if (!is_numeric($v)) {
                            return $conn->quote($v, \PDO::PARAM_STR);
                        }
                        return $v;
                    }, $value);
                    $exprs[] = $field . ' IN (' . implode(', ', $value) . ')';

                } else {
                    $exprs[] = $field . $sign . $select->createNamedParameter($value);
                }
            }
            if (!empty($exprs)) {
                $select->andWhere(implode(' OR ', $exprs));
            }
        }
    }

    protected function hasAnySearchParam()
    {
        $search = $this->getConfig('search');
        if (empty($search)) {
            return false;
        }

        foreach ($search as $s => $info) {
            $value = $this->getRequest()->getParam($s);
            if (strlen($value) > 0) {
                if ($info->type == ConfigBuilder::TYPE_BOOL && !$value) {
                    continue;
                }
                return true;
            }
        }

        return false;
    }


    protected function getDefaultRouteName()
    {
        return $this->getConfig('default_route_name', 'admin.crud');
    }

    protected function getSearchFields()
    {
        $table = $this->getConfig('table');
        return $table->get('fields')->toArray();
    }

    protected function getTableName()
    {
        $tableName = $this->getConfig('tableName');
        if (empty($tableName)) {
            list($tableName) = explode('_', get_called_class());
            $tableName = strtolower($tableName);
        }

        return $tableName;
    }

    public function getNotValidatedFormMessage()
    {
        return 'Corrija os erros informados.';
    }

}
