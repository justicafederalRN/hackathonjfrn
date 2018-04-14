<?php
namespace Admin\Controller;

use Admin\Controller\CrudController;
use Application;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use W5n\Model\Model;
use W5n\Templating\View;

class NestedController extends CrudController
{

    protected $parentConfigKey = null;
    protected $parentConfig    = null;
    protected $parent          = null;
    protected $configKey       = null;

    public function __construct(Application $app, Request $request)
    {
        parent::__construct($app, $request);
    }

    protected function getParentConfig($config, $default = null)
    {
        if (empty($this->parentConfig)) {
            $this->parentConfig = $this->getApplication()->loadConfig(
                $this->getParentConfigKey()
            );
        }
        return isset($this->parentConfig[$config]) ? $this->parentConfig[$config] : $default;
    }

    public function getDefaultRouteName()
    {
        return 'admin.crud.nested';
    }

    public function getRouteUrl($route, array $params = array())
    {
        if (!isset($params['config'])) {
            $params['config'] = $this->getRequest()->attributes->get('config');
        }

        if (!isset($params['parentConfig'])) {
            $params['parentConfig'] = $this->getRequest()->attributes->get('parentConfig');
        }

        if (!isset($params['parentId'])) {
            $params['parentId'] = $this->getRequest()->attributes->get('parentId');
        }

        return parent::getRouteUrl($route, $params);
    }

    public function modifyView(View $view)
    {
        parent::modifyView($view);
        $view->addDefaultRouteParam(
            'parentConfig', $this->getRequest()->attributes->get('parentConfig')
        );
        $view->addDefaultRouteParam(
            'parentId', $this->getRequest()->attributes->get('parentId')
        );
        $view->addDefaultRouteParam(
            'config', $this->getRequest()->attributes->get('config')
        );
    }

    protected function getConfigKey($configKey = null)
    {
        if (!empty($configKey)) {
            return $configKey;
        }

        if (empty($this->configKey)) {
            $this->parentConfigKey = $this->getRequest()->attributes->get('parentConfig');
            $this->configKey       = $this->getRequest()->attributes->get('config');
            $configFiles           = $this->getApplication()->findAllFiles(
                $this->parentConfigKey . '_' . $this->configKey . '.php', 'config'
            );

            if (!empty($configFiles)) {
                $this->configKey = $this->parentConfigKey . '_' . $this->configKey;
            }
        }

        return $this->configKey;
    }

    public function init()
    {
        parent::init();
        $config = $this->getConfig();
        $gender = isset($config['gender']) && $config['gender'] == 'f' ? 'a' : 'o';
        $this->getUiManager()->setPageTitle(
            $config['plural'] . ' d' . $gender . ' ' . $this->getParentConfig('singular') .
            ' "' . $this->getParentDisplayValue() . '"'
        );


        $parentCrumb = $this->getUiManager()->popCrumb();

        $this->getUiManager()->addCrumb(
            $this->getParentConfig('plural'), $this->getParentListUrl()
        );

        $this->getUiManager()->addCrumb(
            $this->getParentDisplayValue(),
            $this->getRouteUrl(
                'admin.crud',
                [
                    'config' => $this->getParentConfigKey(),
                    'action' => 'update',
                    'id' => $this->getparent()->id()
                ]
            )
        );

        $this->getUiManager()->addCrumb(
            $parentCrumb['name'],
            $parentCrumb['link'],
            $parentCrumb['icon'],
            $parentCrumb['attrs']
        );

    }

    protected function getparentdisplayvalue()
    {
        return $this->getParent()->{$this->getParentDisplayField()};
    }

    private function getParentListUrl()
    {
        return $this->getRouteUrl('admin.crud', ['config' => $this->getParentConfigKey()]);
    }

    public function preIndex()
    {
        parent::preIndex();
        $this->getUiManager()->addPageAction(
            'parent_list',
            $this->getParentConfig('plural'),
            $this->getParentListUrl(),
            $this->getConfig('parent_icon', 'arrow-left'),
            null,
            true,
            [],
            -INF
        );
    }

    protected function triggerAfterCreateModel(Model $model)
    {
        parent::triggerAfterCreateModel($model);
        $model->{$this->getParentFkField()} = $this->getParentId();
    }

    protected function modifyListQuery(QueryBuilder $builder, $isCountQuery = false)
    {
        parent::modifyListQuery($builder, $isCountQuery);
        $builder->andWhere(
            $this->getParentFkField() . '=' . $builder->createNamedParameter(
                $this->getParentId()
            )
        );
    }

    protected function getParentId()
    {
        return $this->getRequest()->attributes->get('parentId');
    }

    protected function getParentFkField()
    {
        return $this->getConfig('parent_fk_field');
    }

    protected function getParentDisplayField()
    {
        return $this->getConfig('parent_display_field');
    }

    public function getParentConfigKey()
    {
        return $this->getRequest()->attributes->get('parentConfig');
    }

    /**
     * @return \W5n\Model\Model
     */
    public function getParent()
    {
        if (empty($this->parent)) {
            $parentModel  = $this->getParentConfig('model');
            /* @var $parent Model */
            $this->parent = \call_user_func([$parentModel, 'findById'], $this->getParentId());
        }
        return $this->parent;
    }

}
