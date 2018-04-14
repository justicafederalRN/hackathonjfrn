<?php
namespace Mailer;

use Application;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_NullTransport;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use W5n\Exception;
use W5n\Module;

class MailerModule extends Module
{

    public function initServices(Application $app)
    {
        $app['mailer_transport'] = function($app) {
            $config = $app->loadConfig('mailer');
            if (empty($config['type'])) {
                throw new Exception('Mailer service must have a "type" option set.');
            }

            $type      = strtolower($config['type']);
            $transport = null;

            switch ($type) {
                case 'smtp':
                    $transport = new Swift_SmtpTransport(
                        $config['smtp_host'], $config['smtp_port'], $config['smtp_security']
                    );
                    $transport->setUsername($config['smtp_username']);
                    $transport->setPassword($config['smtp_password']);

                    return $transport;
                case 'sendmail':
                    return new Swift_SendmailTransport($config['sendmail_command']);
                case 'mail':
                    return new Swift_MailTransport();
                case 'null':
                    return new Swift_NullTransport();
                default:
                    throw new Exception('Mailer type "' . $type . '" not known.');
            }
        };

        $app['mailer'] = function ($app) {
            return new Swift_Mailer($app['mailer_transport']);
        };

        $app['mailer_message'] = $app->factory(function ($app) {
            return new Message($app['mailer']);
        });
    }

}
