<?php
namespace Frontend\Controller;

use W5n\Controller\DefaultController;

class FrontendController extends DefaultController
{
    /**
     * @var \Detection\MobileDetect
     */
    private $mobileDetector = null;

    public function __construct(\Application $app, \Symfony\Component\HttpFoundation\Request $request)
    {
        parent::__construct($app, $request);
        $this->mobileDetector = new \Detection\MobileDetect();
    }


    public function createView($file = null, array $data = array())
    {
        $view = parent::createView($file, $data);


        $view->isMobile = $this->mobileDetector->isMobile();
        $view->isTablet = $this->mobileDetector->isTablet();
        $view->isPhone  = $view->isMobile && !$view->isTablet;

        return $view;
    }

    /**
     *
     * @return \Detection\MobileDetect
     */
    public function getMobileDetector()
    {
        return $this->mobileDetector;
    }

    public function getAccessDevice()
    {
        if (!$this->getMobileDetector()->isMobile()) {
            return 'desktop';
        } elseif ($this->getMobileDetector()->isTablet()) {
            return 'tablet';
        } elseif ($this->getMobileDetector()->isMobile())  {
            return 'mobile';
        }

        return 'unknown';
    }

}
