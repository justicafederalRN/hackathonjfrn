<?php
namespace Admin\Controller;

class DashboardController extends AdminController
{

    protected $layout = 'layout/admin';

    public function indexAction()
    {
        $this->getUiManager()->setPageTitle('Dashboard');
        return $this->createView('admin/dashboard')->render();
    }
}
