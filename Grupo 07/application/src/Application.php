<?php
use \W5n\Application as BaseApplication;
use W5n\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use GO\Scheduler;
use Importacao\Model\Titulo;
use Application\NLPService;
use Application\Repository\ProcessoRepository;

class Application extends BaseApplication
{
    public function init()
    {
        parent::init();
        date_default_timezone_set('America/Recife');
        $this->initTableFilters();
        $isDev = preg_match('#(localhost|\.test|\.dev)#i', $this->getMasterRequest()->getHost());
        $this->setEnvironment($isDev ? self::ENV_DEVELOPMENT : self::ENV_PRODUCTION);

        $app = $this;

        $app['nlp'] = function ($app) {
            return new NLPService($app['db']);
        };

        $app['processoRepository'] = function ($app) {
            return new ProcessoRepository($app['db']);
        };
    }

    private function initTableFilters()
    {
        \W5n\Table::addFilter('default-date', function ($v) {
            return date('d/m/Y', strtotime($v));
        });

        \W5n\Table::addFilter('default-datetime', function ($v) {
            if (empty($v)) {
                return '-';
            }
            return date('d/m/Y H:i', strtotime($v));
        });

        \W5n\Table::addFilter('yes-no', function ($v) {
            $template = '<span class="label label-%s">%s</span>';

            if ($v) {
                return sprintf($template, 'success', 'Sim');
            }

            return sprintf($template, 'danger', 'Não');
        });

    }

    public function schedule(Scheduler $scheduler)
    {
    }

    protected function registerModules(Request $req)
    {
        return [
            new \Ajax\AjaxModule(),
            new \Admin\AdminModule(),
            new \ApplicationModule(),
            new \Db\DatabaseModule(),
            new \Mailer\MailerModule(),
            new \Session\SessionModule(),
            new \Usuarios\UsuariosModule(),
            new \View\ViewModule(),
            new \Configuracoes\ConfiguracoesModule(),
            new \Dashboard\DashboardModule(),
            new \Processos\ProcessosModule()
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getMasterRequest()
    {
        return $this['request'];
    }

    public function routeUrl($routeName, array $params = [], $referenceType = Router::ABSOLUTE_PATH)
    {
        return Router::generate($this->getMasterRequest(), $routeName, $params, $referenceType);
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this['mailer'];
    }

    /**
     * @return \Mailer\Message
     */
    public function createMailMessage()
    {
        /* @var $message \Mailer\Message */
        $message = $this['mailer_message'];
        $message->setFrom('diocesano@consultern.com.br', 'Diocesano Santa Luzia');

        return $message;
    }

    public function sendEmail($to, $subject, $content) 
    {
        $email = $this->createMailMessage();
        $email->setBody($content, 'text/html');

        if (!is_array($to)) {
            $to = [$to];
        }

        $to = array_values($to);

        foreach ($to as $idx => $e)  {
            if ($idx == 0) {
                $email->addTo($e);
            } else {
                $email->addCc($e);
            }
        }

        $email->setSubject($subject);

        try {
            $this->getMailer()->send($email);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function sendSms($to, $msg, $referenceId = null)
    {
        $encoded      = urlencode($msg);
        $destinatario = preg_replace('#[^0-9]#', '', $to);

        $config  = $this->loadConfig('sms-api');

        $url     = $config['baseUrl'] . $config['endpoints']['send']['uri'];

        $params  = [
            'user'         => $config['login'],
            'password'     => $config['password'],
            'msg'          => $msg,
            'destinatario' => $destinatario
        ];

        if (!empty($referenceId)) {
            $params['externalkey'] = $referenceId;
        }

        $url .= '?' . http_build_query($params);
        try {
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($handle);
            curl_close($handle);
            if (empty($result)) {
                return false;
            }

            $responseCode = preg_replace('#;.*$#', '', $result);
            $id           = preg_replace('#^.*?;#', '', $result);

            if ($responseCode == '6') {
                $this->deleteSmsCacheFile();
                return $id;
            }

            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    private function getSmsCacheFilePath()
    {
        return CACHE_PATH . 'sms.cache';
    }

    private function deleteSmsCacheFile()
    {
        unlink($this->getSmsCacheFilePath());
    }

    private function hasSmsCacheFile()
    {
        return file_exists($this->getSmsCacheFilePath());
    }

    public function getSmsAvailableCount()
    {
        if ($this->hasSmsCacheFile()) {
            return file_get_contents($this->getSmsCacheFilePath());
        }
        $smsCacheFile = $this->getSmsCacheFilePath();


        $config  = $this->loadConfig('sms-api');

        $url     = $config['baseUrl'] . $config['endpoints']['credit']['uri'];

        $params  = [
            'user'         => $config['login'],
            'password'     => $config['password']
        ];


        $url .= '?' . http_build_query($params);

        try {
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($handle);
            curl_close($handle);
            if (empty($result)) {
                return false;
            }

            $responseCode = preg_replace('#;.*$#', '', $result);
            $credits      = preg_replace('#^.*?;#', '', $result);

            if ($responseCode == '2') {
                $h = fopen($smsCacheFile, 'w+');
                fwrite($h, $credits);
                fclose($h);
                return $credits;
            }

            return false;
        } catch (\Exception $ex) {
            return false;
        }
        
    }
}
