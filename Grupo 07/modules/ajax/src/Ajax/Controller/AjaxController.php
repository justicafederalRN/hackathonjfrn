<?php
namespace Ajax\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends \Admin\Controller\AdminController
{
    private function isValid($handler)
    {
        return $this->getRequest()->isXmlHttpRequest() && \Ajax\AjaxManager::hasHandler($handler);
    }

    public function handleAction($handler)
    {
        if (!$this->isValid($handler)) {
            return new JsonResponse(['message' => 'requisição inválida'], 400);
        }

        $response = \Ajax\AjaxManager::callHandler(
            $handler,
            $this->getRequest(),
            new JsonResponse(),
            $this->getApplication()
        );

        if (empty($response)) {
            return new JsonResponse(['message' => 'requisição inválida'], 400);
        }

        return $response;
    }
}
