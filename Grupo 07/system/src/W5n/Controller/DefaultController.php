<?php
namespace W5n\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use W5n\Routing\Router;

use W5n\Templating\View;

class DefaultController
{
    protected $layout        = null;
    protected $application   = null;
    protected $request       = null;
    protected $layoutEnabled = true;

    public function __construct(\Application $app, Request $request)
    {
        $this->application = $app;
        $this->request     = $request;
    }

    public function viewResponse($file, array $data = array(), $status = 200, array $headers = array())
    {
        $view = $this->createView($file, $data);
        return $this->response($view->render(), $status, $headers);
    }

    public function jsonResonse($data, $status = 200, array $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }

    public function response($content = null, $status = 200, array $headers = array())
    {
        if ($content instanceof View) {
            $content = $content->render();
        }
        return new Response($content, $status, $headers);
    }

    /**
     * @param string $file
     * @param array $data
     * @return View
     */
    public function createView($file = null, array $data = array())
    {
        $app  = $this->getApplication();
        $view = $app['view'];

        if (!empty($file)) {
            $view->setFile($file);
        }

        if (!empty($data)) {
            $view->setParameters($data);
        }

        if ($this->layoutEnabled && !empty($this->layout)) {
            $view->extend($this->layout);
        }

        $this->modifyView($view);
        return $view;
    }

    public function modifyView(View $view)
    {
        $view->application = $this->getApplication();
        $view->request     = $this->getRequest();
        return $this;
    }

    public function redirect($url, $status = 302, array $headers = array())
    {
        return new RedirectResponse($url, $status, $headers);
    }

    public function routeRedirect($routeName, array $routeParams = array(), $status = 302, array $headers = array())
    {
        return $this->redirect(
            Router::generate($this->getRequest(), $routeName, $routeParams),
            $status,
            $routeParams
        );
    }

    public function routeUrl($routeName, array $routeParams = array())
    {
        return Router::generate($this->getRequest(), $routeName, $routeParams);
    }

    public function currentRouteRedirect()
    {
        return $this->routeRedirect(
            $this->getRequest()->attributes->get('_route')
        );
    }

    /**
     * @return \Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function disableLayout()
    {
        return $this->setLayoutEnabled(false);
    }

    public function enableLayout()
    {
        return $this->setLayoutEnabled(true);
    }

    public function isLayoutEnabled()
    {
        return $this->layoutEnabled;
    }

    public function setLayoutEnabled($enabled)
    {
        $this->layoutEnabled = (bool) $enabled;
        return $this;
    }


    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->application['session'];
    }

    public function addFlash($type, $message)
    {
        $this->getSession()->getFlashBag()->add($type, $message);
        return $this;
    }

    public function addErrorFlash($message)
    {
        $this->addFlash('danger', $message);
    }

    public function addSuccessFlash($message)
    {
        $this->addFlash('success', $message);
    }

    public function addWarningFlash($message)
    {
        $this->addFlash('warning', $message);
    }

    public function addInfoFlash($message)
    {
        $this->addFlash('info', $message);
    }

}
