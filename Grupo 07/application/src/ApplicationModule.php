<?php
use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class ApplicationModule extends \W5n\Module
{
    function init(\Application $app, Request $req, RouteCollection $routes)
    {
        \W5n\Model\Field\Image::setPrefixUri($req->getUriForPath('/assets/uploads/images/'));
        \W5n\Model\Field\Image::setPrefixPath(ASSETS_PATH . 'uploads/images');
        \W5n\Model\Field\Upload::setPrefixUri($req->getUriForPath('/assets/uploads/files/'));
        \W5n\Model\Field\Upload::setPrefixPath(ASSETS_PATH . 'uploads/files');
    }

    public function initRoutes(RouteCollection $routes)
    {
        parent::initRoutes($routes);
    }
}
