<?php
namespace Dashboard\Admin;

use Admin\Controller\AdminController;
use Importacao\ActivesoftBoletosImporter;
use Importacao\ActivesoftCsvImporter;
use Importacao\Model\Aluno;
use Importacao\Model\Responsavel;
use Importacao\Model\Titulo;

class DashboardController extends AdminController
{
    protected $layout = 'layout/admin';

    public function dashboardAction()
    {
        $isToday    = $this->getRequest()->attributes->get('isToday', false);
        $isFiveDays = $this->getRequest()->attributes->get('isFiveDays', false);
        $isPrint   = (bool) $this->getRequest()->query->get('print', false);

        if (!$isPrint) {
            $this->getUiManager()->addPageAction(
                'print', 'Imprimir', '?print=1', 'print'
            );
        }

        $month = $day = $year = null;

        $data = [];

        if ($isToday) {
            $month = date('n');
            $year  = date('Y');
            $day   = date('j');
            $title = 'Títulos de Hoje';
        } elseif ($isFiveDays) {
            $time  = strtotime('+5 days');
            $month = date('n', $time);
            $year  = date('Y', $time);
            $day   = date('j', $time);
            $title = 'Títulos que vencem em 5 dias';
        } else {
            $month = $this->getRequest()->get('m', date('n'));
            $year  = $this->getRequest()->get('a', date('Y'));
            $title = null;
        }

        $feriados = \Importacao\Model\Feriado::getAll($month, $year, $day);

        $data = [
            'titulos'  => \Importacao\Model\Titulo::getAllFromMonth($day, $month, $year),
            'mes'      => $month,
            'ano'      => $year,
            'title'    => $title,
            'isToday'  => $isToday,
            'feriados' => $feriados
        ];

        $activeMenu = 'dashboard';

        if ($isToday) {
            $activeMenu = 'dashboard-today';
        } elseif ($isFiveDays) {
            $activeMenu = 'dashboard-five-days';
        }


        $this->getUiManager()->setActiveMenu($activeMenu);
        $this->getUiManager()->addCrumb('Consulte');
        $this->getUiManager()->addCrumb('Títulos');

        if ($isPrint) {
            $this->layout  = 'layout/clean';
        }

        $data['isPrint'] = $isPrint;

        return $this->createView('dashboard/admin/dashboard', $data);
    }

}
