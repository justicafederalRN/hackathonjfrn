<?php
namespace W5n;

use W5n\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use GO\Scheduler;

class Application extends \Pimple\Container implements HttpKernelInterface
{
    const ENV_PRODUCTION  = 'prod';
    const ENV_DEVELOPMENT = 'dev';
    const ENV_STAGING     = 'staging';

    protected static $instances       = array();
    protected static $defaultInstance = null;

    protected $environment     = null;
    protected $modules         = array();
    protected $applicationPath = null;
    protected $modulesPath     = null;
    protected $systemPath      = null;
    protected $configCache     = array();

    public function __construct($env = self::ENV_DEVELOPMENT, $setPathFromConstants = true)
    {
        parent::__construct();
        $this->registerAutoload();
        $this->setEnvironment($env);
        if ($setPathFromConstants) {
            $this->setPathsFromConstants();
        }
        if (empty(self::$instances)) {
            self::setDefault($this);
        }
        self::$instances[] = $this;
    }

    public function run($handle = true)
    {
        $request         = Request::createFromGlobals();
        $this['request'] = $request;

        $this->processOptions();
        $this->init();
        $this->bootstrapModules($request);

        if ($handle) {
            $catchExceptions = isset($this['catchExceptions']) ? $this['catchExceptions'] : false;
            try {
                $request->attributes->set('application', $this);
                $this->handle($request, self::MASTER_REQUEST, $catchExceptions);
            } catch (\Exception $ex) {
                if ($catchExceptions) {
                    return '';
                }
                throw $ex;
            }
        }
    }

    public function schedule(Scheduler $scheduler)
    {}

    protected function bootstrapModules(Request $req)
    {
        $modules = $this->registerModules($req);

        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as $m) {
                if (!is_object($m) || !($m instanceof Module)) {
                    throw new Exception(
                        'All registered modules must be instances of \\W5n\\Module.'
                    );
                }
                $m->initServices($this);
                $m->initRoutes(Routing\Router::getRouteCollection());
                $m->init($this, $req, Routing\Router::getRouteCollection());
            }
        }
    }

    protected function registerModules(Request $r)
    {}

    private function requireBootstrapFile($file)
    {
        require_once $file;
    }

    protected function getModules()
    {
        if (!empty($this->modules)) {
            return $this->modules;
        }

        $this->modules = array_filter(glob($this->getModulesPath() . '*'), function ($p) {
            return is_dir($p);
        });

        return $this->modules;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $request->attributes->add(\W5n\Routing\Router::match($request));
        $resolver = new Controller\ControllerResolver($this, $request);

        $controller = $resolver->getController($request);
        $parameters = $resolver->getArguments($request, $controller);

        $response = null;

        if (isset($controller['0']) 
            && is_object($controller[0]) 
            && method_exists($controller[0], 'before')
        ) {
            $response = call_user_func([$controller[0], 'before']);
        }

        if (empty($response)) {
            $response = call_user_func_array($controller, $parameters);
        }

        if ($response instanceof \W5n\Templating\View) {
            $response = $response->render();
        }

        if (!($response instanceof Response) && !is_string($response)) {
            is_callable($controller, false, $callableName);

            throw new Exception(
                sprintf(
                    'Controller "%s" must return a string or a Response object.',
                    $callableName
                )
            );
        }
        if (is_string($response)) {
            $response = new Response($response);
        }
        $response->send();
    }

    public function registerAutoload()
    {
        $app = $this;
        spl_autoload_register(function ($class) use ($app) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (($file = $app->findFile($class, 'src')) !== false) {
                require_once $file;
            }
        }, true);
    }

    public function init()
    {

    }

    public function setPathsFromConstants()
    {
        $this->setApplicationPath(APPLICATION_PATH);
        $this->setModulesPath(MODULES_PATH);
        $this->setSystemPath(SYSTEM_PATH);
    }

    public function getApplicationPath()
    {
        return $this->applicationPath;
    }

    public function getModulesPath()
    {
        return $this->modulesPath;
    }

    public function getSystemPath()
    {
        return $this->systemPath;
    }

    public function setApplicationPath($applicationPath)
    {
        $this->applicationPath = $applicationPath;
        return $this;
    }

    public function setModulesPath($modulesPath)
    {
        $this->modulesPath = $modulesPath;
        return $this;
    }

    public function setSystemPath($systemPath)
    {
        $this->systemPath = $systemPath;
        return $this;
    }

    public function setEnvironment($env)
    {
        $this->environment = $env;
        return $this;
    }

    public function isInEnvironment($env)
    {
        return $this->getEnvironment() == $env;
    }

    public function isInProduction()
    {
        return $this->isInEnvironment(self::ENV_PRODUCTION);
    }

    public function isInStaging()
    {
        return $this->isInEnvironment(self::ENV_STAGING);
    }

    public function isInDevelopment()
    {
        return $this->isInEnvironment(self::ENV_DEVELOPMENT);
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public static function getDefault()
    {
        return self::$defaultInstance;
    }

    public static function setDefault(Application $app)
    {
        self::$defaultInstance = $app;
    }

    public static function getAllInstances()
    {
        return self::$instances;
    }

    public function findFile($path, $folder = null, $inApplication = true, $inModules = true, $inSystem = true)
    {
        $path  = ltrim($path, '/\\');
        $file  = empty($folder) ? $path : $folder . DIRECTORY_SEPARATOR . $path;

        //search in application first...
        if ($inApplication) {
            $searchPath = $this->getApplicationPath() . $file;
            if (file_exists($searchPath)) {
                return $searchPath;
            }
        }


        //then in all loaded modules...
        if ($inModules) {
            foreach ($this->getModules() as $m) {
                $searchPath = $m . DIRECTORY_SEPARATOR . $file;
                if (file_exists($searchPath)) {
                    return $searchPath;
                }
            }
        }

        //and then in system.
        if ($inSystem) {
            $searchPath = $this->getSystemPath() . $file;
            if (file_exists($searchPath)) {
                return $searchPath;
            }
        }
        return false;
    }

    public function findAllFiles($path, $folder = null, $inApplication = true, $inModules = true, $inSystem = true)
    {
        $path  = ltrim($path, '/\\');
        $file  = empty($folder) ? $path : $folder . DIRECTORY_SEPARATOR . $path;

        $allFiles = array();

        if ($inApplication) {
            $searchPath = $this->getApplicationPath() . $file;

            if (file_exists($searchPath)) {
                $allFiles[] = $searchPath;
            }
        }

        if ($inModules) {
            foreach ($this->getModules() as $m) {
                $searchPath = $m . DIRECTORY_SEPARATOR . $file;
                if (file_exists($searchPath)) {
                    $allFiles[] = $searchPath;
                }
            }
        }

        if ($inSystem) {
            $searchPath = $this->getSystemPath() . $file;
            if (file_exists($searchPath)) {
                $allFiles[] = $searchPath;
            }
        }

        return $allFiles;
    }

    public function loadConfig($file, $cache = true)
    {
        if ($cache && isset($this->configCache[$file])) {
            return $this->configCache[$file];
        }

        //in system
        $configFiles = $this->findAllFiles($file . '.php', 'config', false, false);
        $configFiles = array_merge(
            $configFiles,
            $this->findAllFiles($file . '_' . $this->getEnvironment() .  '.php', 'config', false, false)
        );

        //in modules
        $configFiles = array_merge(
            $configFiles,
            $this->findAllFiles($file . '.php', 'config', false, true, false)
        );

        $configFiles = array_merge(
            $configFiles,
            $this->findAllFiles($file . '_' . $this->getEnvironment() .  '.php', 'config', false, true, false)
        );

        //in application
        $configFiles = array_merge(
            $configFiles,
            $this->findAllFiles($file . '.php', 'config', true, false, false)
        );

        $configFiles = array_merge(
            $configFiles,
            $this->findAllFiles($file . '_' . $this->getEnvironment() .  '.php', 'config', true, false, false)
        );

        $config      = array();

        foreach ($configFiles as $f) {
            $result = require $f;
            if (!is_array($result)) {
                throw new Exception(sprintf(
                    'Config file "%s" must return an array. "%s" given.',
                    $f,
                    gettype($result)
                ));
            }
            $config = array_merge($config, $result);
        }


        if ($cache) {
            $this->configCache[$file] = $config;
        }

        return $config;
    }

    public function processOptions()
    {
        if (isset($this['displayErrors']) && $this['displayErrors']) {
            $run     = new \Whoops\Run();
            $handler = null;
            if (isset($this['errorHandler'])) {
                $handler = $this['errorHandler'];
            } else {
                $handler = new \Whoops\Handler\PrettyPageHandler();
            }
            $run->pushHandler($handler);
            $run->register();
        } else {
            error_reporting(false);
            ini_set('display_errors', false);
        }
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDefaultDatabase()
    {
        return $this['db'];
    }

}
