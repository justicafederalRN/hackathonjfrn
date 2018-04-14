<?php
namespace Processos\Controller;

class AdminController extends \Admin\Controller\AdminController
{
    protected $layout = 'layout/admin';

    public function detailAction($id)
    {
        $app        = $this->getApplication();
        $repository = $app['processoRepository'];
        $nlp        = $app['nlp'];

        $processo = $repository->findById($id);
        $ui       = $app['admin.ui_manager'];
        $ui->setPageTitle('Processo Nº ' . $processo['numero_processo']);
        $ui->setActiveMenu('processos');

        $probability = null;
        $tutela      = $nlp->predictTutela($id, $probability);

        $procedenteProbabilitity = null;
        $relatedProcesses        = null;
        $procedente              = $nlp->predictProcedencia(
            $id,
            $procedenteProbabilitity,
            $relatedProcesses
        );

        return $this->createView(
            'processos/admin/detail',
            [
                'processo'              => $processo,
                'boxed'                 => false,
                'tutela'                => $tutela,
                'tutelaProbability'     => $probability,
                'procedente'            => $procedente,
                'relatedProcesses'      => $relatedProcesses,
                'procedenteProbability' => $procedenteProbabilitity,
                'processNumberInfo'     => $nlp->decodeProcessNumber($processo['numero_processo'])
            ]
        );
    }
}
