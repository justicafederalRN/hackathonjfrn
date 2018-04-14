<?php
namespace Application\Controller;

use Diferenciais\Model\Diferencial;
use Frontend\Controller\FrontendController;
use Leads\Model\Lead;
use Noticias\Model\Noticia;
use Vitrines\Model\Vitrine;
use W5n\Model\Form;
use W5n\Routing\Router;

class SiteController extends FrontendController
{
    protected $layout = 'layouts/site';

    public function createView($file = null, array $data = array())
    {
        $view = parent::createView($file, $data);
        $model = new Lead();
        $form  = new Form(
            $model,
            Router::generate($this->getRequest(), 'contato')
        );
        $view->model = $model;
        $view->form  = $form;
        return $view;
    }

    public function indexAction()
    {
        $news = Noticia::findAllForHome();
        $countNews   = count($news);
        $currentNews = $this->getRequest()->query->get('n', 0);
        if (!filter_var($currentNews, FILTER_VALIDATE_INT) || $currentNews < 0 || $currentNews >= $countNews) {
            $currentNews = 0;
        }
        $data = [
            'diferenciais' => Diferencial::findAll(),
            'imagens'      => Vitrine::findAllForHome(),
            'news'         => $news,
            'currentNews'  => $currentNews
        ];
        return $this->createView('site/index', $data);
    }

    public function contatoAction()
    {
        $data = [
            'success' => false
        ];

        $model = new Lead();
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect('/');
        }

        if ($this->getRequest()->isMethod('POST') && $this->getRequest()->isXmlHttpRequest()) {
            $model->populateFromArray($this->getRequest()->request->all());
            $model->ip          = $this->getRequest()->getClientIp();
            $model->dispositivo = $this->getAccessDevice();
            $model->navegador   = $this->getRequest()->headers->get('User-Agent');

            if ($model->save()) {
                $data['success'] = true;
            } else {
                $data['success'] = false;
                $data['errors'] = $model->getErrors();
            }
        }

        return $this->jsonResonse($data);
    }
}
