<?php
namespace Admin\Ui;

class UiManager
{
    protected $menus         = [];
    protected $title         = null;
    protected $description   = null;
    protected $metaTitle     = null;
    protected $metas         = [];
    protected $userMenu      = [];
    protected $username      = null;
    protected $userActions   = null;
    protected $userImage     = null;
    protected $projectName   = null;
    protected $pageActions   = [];
    protected $breadCrumb    = [];
    protected $activeMenu    = null;
    protected $activeSubmenu = null;
    protected $footerMessage = null;

    protected static $defaultInstance = array();

    public function __construct()
    {}

    /**
     * @return \Admin\Ui\UiManager
     */
    public function addMenu(
        $id, $name, $icon = null, $link = null, $badge = null,
        $weight = 0, $visible = true
    ) {
        $this->menus[$id] = array(
            'name'     => $name,
            'icon'     => $icon,
            'link'     => $link,
            'weight'   => $weight,
            'children' => array(),
            'visible'  => $visible,
            'badge'    => $badge
        );

        return $this;
    }

     /**
     * @return \Admin\Ui\UiManager
     */
    public function ensureMenu(
        $id, $name, $icon = null, $link = null, $badge = null,
        $weight = 0, $visible = true
    ) {
        if (!$this->hasMenu($id)) {
            $this->addMenu(
                $id, $name, $icon, $link, $badge, $weight, $visible
            );
        }

        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function setActiveMenu($menu, $submenu = null)
    {
        if (!is_null($menu)) {
            $this->activeMenu = $menu;
        }

        if (!is_null($submenu)) {
            $this->activeSubmenu = $submenu;
        }

        return $this;
    }

    public function getActiveMenu()
    {
        return $this->activeMenu;
    }

    public function getActiveSubmenu()
    {
        return $this->activeSubmenu;
    }

    public function isMenuActive($menu, $submenu = null)
    {
        if ($this->activeMenu != $menu) {
            return false;
        }

        return is_null($submenu) || $submenu == $this->activeSubmenu;
    }

    public function isAnyMenuActive()
    {
        return !empty($this->activeMenu);
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function addCrumb($name, $link = null, $icon = null, $attrs = array())
    {
        if (strlen($name) == 0) {
            return;
        }
        $this->breadCrumb[] = array(
            'name'  => $name,
            'link'  => $link,
            'icon'  => $icon,
            'attrs' => $attrs
        );
        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function preprendCrumb($name, $link = null, $icon = null, $attrs = array())
    {
        if (strlen($name) == 0) {
            return;
        }
        $data = array(
            'name'  => $name,
            'link'  => $link,
            'icon'  => $icon,
            'attrs' => $attrs
        );
        array_unshift($this->breadCrumb, $data);
        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function addTab(
        $id, $name, $link = null, $icon = null, $active = false, $weight = null
    ) {
        if (is_null($weight)) {
            $weight = count($this->tabs) + 1;
        }
        $this->tabs[$id] = array(
            'name'   => $name,
            'link'   => $link,
            'icon'   => $icon,
            'active' => $active,
            'weight' => $weight
        );
        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function setActiveTab($id)
    {
        foreach ($this->tabs as $tabId => &$info) {
            $info['active'] = $tabId == $id;
        }
        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function removeTab($id)
    {
        unset($this->tabs[$id]);
        return $this;
    }

    public function popCrumb()
    {
        return array_pop($this->breadCrumb);
    }

    public function clearTabs()
    {
        $this->tabs = array();
        return $this;
    }

    public function getTabs()
    {
        uasort($this->tabs, array($this, 'sortByWeight'));
        return $this->tabs;
    }

    public function getBreadCrumb()
    {
        return $this->breadCrumb;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function clearBreadCrumb()
    {
        $this->breadCrumb = array();
        return $this;
    }

    public function hasAnyCrumb()
    {
        return !empty($this->breadCrumb);
    }

    public function genderString($gender, $maleString, $femaleString)
    {
        return $gender !== 'f' ? $maleString : $femaleString;
    }



    /**
     * @return \Admin\Ui\UiManager
     */
    public function removeMenu($menuId)
    {
        unset($this->menus[$menuId]);
        return $this;
    }

    public function hasAnyMenu()
    {
        return !empty($this->menus);
    }

    public function getMenuData()
    {
        uasort($this->menus, array($this, 'sortByWeight'));
        $data = array();
        foreach ($this->menus as $menuId => $menu) {
            if (!empty($menu['children']))
                uasort($menu['children'], array($this, 'sortByWeight'));

            if (is_callable($menu['visible'])) {
                $menu['visible'] = call_user_func($menu['visible'], $menuId, $menu);
            }


            if (empty($menu['children'])) {
                unset($menu['children']);
            } else {

                foreach ($menu['children'] as $submenuId => &$submenu) {
                    if (is_callable($submenu['visible'])) {
                        $submenu['visible'] = call_user_func($submenu['visible'], $menuId, $submenuId, $submenu, $menu);
                    }

                    if (empty($submenu['children'])) {
                        unset($submenu['children']);
                    }
                }
            }

            $data[$menuId] = $menu;
        }

        return $data;
    }

    protected function sortByWeight($a, $b)
    {
        $aWeight = isset($a['weight']) ? $a['weight'] : 0;
        $bWeight = isset($b['weight']) ? $b['weight'] : 0;

        if ($aWeight < $bWeight) {
            return -1;
        } elseif ($aWeight > $bWeight) {
            return 1;
        } else {
            return strcasecmp($a['name'], $b['name']);
        }
    }

    protected function sortByWeightDesc($a, $b)
    {
        $aWeight = isset($a['weight']) ? $a['weight'] : 0;
        $bWeight = isset($b['weight']) ? $b['weight'] : 0;

        if ($aWeight < $bWeight) {
            return 1;
        } elseif ($aWeight > $bWeight) {
            return -1;
        } else {
            return strcasecmp($a['name'], $b['name']) * -1;
        }

        return -1;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function removeSubmenu($menuId, $submenuId)
    {
        if ($this->hasSubmenu($menuId, $submenuId)) {
            unset($this->menus[$menuId]['children'][$submenuId]);
        }

        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function addSubmenu(
        $menuId, $submenuId, $name,
        $icon = null, $link = null, $badge = null,
        $weight = 0, $visible = true
    ) {
        if (!$this->hasMenu($menuId)) {
            return $this;
        }

        $this->menus[$menuId]['children'][$submenuId] = array(
            'name'     => $name,
            'icon'     => $icon,
            'link'     => $link,
            'weight'   => $weight,
            'visible'  => $visible,
            'badge'    => $badge
        );
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTabs()
    {
        return !empty($this->tabs);
    }

    /**
     * @return bool
     */
    public function hasMenu($id)
    {
        return isset($this->menus[$id]);
    }

    /**
     * @return bool
     */
    public function hasSubmenu($menuId, $submenuId)
    {
        return isset($this->menus[$menuId]['children'][$submenuId]);
    }

    public function getProjectName()
    {
        return $this->projectName;
    }

    public function getPageTitle()
    {
        return $this->title;
    }

    public function hasPageTitle()
    {
        return !empty($this->title);
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
        return $this;
    }

    /**
     * @return \Admin\Ui\UiManager
     */
    public function setPageTitle($pageTitle)
    {
        $this->title = $pageTitle;
        return $this;
    }

    public function hasPageDescription()
    {
        return !empty($this->description);
    }

    public function getPageDescription()
    {
        return $this->description;
    }

    public function setPageDescription($pageDescription)
    {
        $this->description = $pageDescription;
        return $this;
    }

    public function addPageAction(
        $id, $name, $url, $icon = null, $badge = null, $visible = true, $attrs = array(), $weight = 0
    )
    {
        $this->pageActions[$id] = array(
            'name'    => $name,
            'badge'   => $badge,
            'url'     => $url,
            'attrs'   => $attrs,
            'weight'  => $weight,
            'icon'    => $icon,
            'visible' => $visible
        );

        return $this;
    }

    public function addUserAction(
        $id, $name, $url, $icon = null, $attrs = array()
    )
    {
        $this->userActions[$id] = array(
            'separator' => false,
            'name'      => $name,
            'url'       => $url,
            'attrs'     => $attrs,
            'icon'      => $icon
        );

        return $this;
    }

    public function addUserActionSeparator()
    {
        $this->userActions[] = array(
            'separator' => true
        );
        return $this;
    }

    public function removeUserAction($id)
    {
        unset($this->userActions[$id]);
        return $this;
    }

    public function hasAnyUserAction()
    {
        return !empty($this->userActions);
    }

    public function getUserActions()
    {
        return $this->userActions;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function hasUsername()
    {
        return !empty($this->username);
    }

    public function getUserImage()
    {
        return $this->userImage;
    }

    public function setUserImage($userImageUrl)
    {
        $this->userImage = $userImageUrl;
        return $this;
    }

    public function hasUserImage()
    {
        return !empty($this->userImage);
    }

    public function removePageAction($id)
    {
        unset($this->pageActions[$id]);
        return $this;
    }

    public function clearPageActions()
    {
        $this->pageActions = array();
        return $this;
    }

    public function hasPageAction($id)
    {
        return isset($this->pageActions[$id]);
    }

    public function hasAnyPageAction()
    {
        return !empty($this->pageActions);
    }

    public function getPageActions()
    {
        uasort($this->pageActions, array($this, 'sortByWeight'));
        return $this->pageActions;
    }

    public function renderFlashMessages($messages)
    {
        if (empty($messages)) {
            return;
        }
        $out = '';
        foreach ($messages as $type => $m) {
            foreach ($m as $message) {
               //$out .= sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
               $out .= sprintf('<div class="alert alert-%s alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>%s</div>', $type, $message);
            }
        }
        return $out;
    }

    function renderPagination($view, $currentPage, $totalPages, $routeName, $params = array(), $displayPages = 11, $echo = true)
    {
        if ($totalPages < 2) {
            return '';
        }

        $query_string = '';
        $query_params = $_GET;




        $half = floor($displayPages / 2);
        $first_page = max(1, $currentPage - $half);
        $last_page = min($totalPages, max($displayPages, $currentPage + $half));
        $first_page = max(1, $last_page - $displayPages + 1);
        $show_before_dots = $first_page != 1;
        $show_after_dots = $last_page != $totalPages;
        $show_prev = $currentPage > 1;
        $show_next = $currentPage < $totalPages;

        $paginacao = '<div><ul class="pagination pagination-centered">';

        if ($show_prev) {
            $query_params['page'] = $currentPage - 1;

            $href = $view->routeUrl($routeName, $params);
            $paginacao .= '<li class="prev"><a href="'
                . $href . '?' . http_build_query($query_params) . '"'
                . ' title="Página anterior">&laquo;</a></li>';
        }

        if ($show_before_dots) {
            $paginacao .= '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
        }

        for ($i = $first_page; $i <= $last_page; $i++) {
            $class = '';
            $title = 'Ir para página ' . $i;
            $query_params['page'] = $i;
            $tmp_url = $view->routeUrl($routeName, $params);

            $is_current = $i == $currentPage;

            if ($is_current) {
                $class = ' active';
                $title = 'Página atual';
                $tmp_url = '#';
            }

            $paginacao .= '<li class="pagina' . $class . '"><a href="' . $tmp_url . '?' . http_build_query($query_params) . '" title="'
                . $title . '">' . $i . '</a></li>';
        }

        if ($show_after_dots) {
            $paginacao .= '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
        }

        if ($show_next) {
            $query_params['page'] = $currentPage + 1;
            $href = $view->routeUrl($routeName, $params);
            $paginacao .= '<li class="next"><a href="'
                . $href . '?' . http_build_query($query_params) . '"'
                . ' title="Página seguinte">&raquo;</a></li>';
        }

        $paginacao .= '</ul></div>';

        if ($echo) {
            echo $paginacao;
        } else {
            return $paginacao;
        }
    }

    public function reset()
    {
        $this->projectName     = null;
        $this->menus           = array();
        $this->title           = null;
        $this->description     = null;
        $this->breadCrumb      = array();
        $this->tabs            = array();
        $this->activeMenu      = null;
        $this->activeSubmenu   = null;
        $this->pageActions     = array();
        $this->username        = null;
        $this->userActions     = null;
        $this->metaTitle       = null;
        $this->metas           = [];

        return $this;
    }

    public function getFooterMessage()
    {
        return $this->footerMessage;
    }

    public function setFooterMessage($footerMessage)
    {
        $this->footerMessage = $footerMessage;
        return $this;
    }

    public function hasFooterMessage()
    {
        return !empty($this->footerMessage);
    }

}

