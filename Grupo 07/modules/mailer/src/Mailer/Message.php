<?php
namespace Mailer;

class Message extends \Swift_Message
{
    private $mailer = null;

    public function __construct(\Swift_Mailer $mailer)
    {
        parent::__construct(null, null, 'text/html', 'utf-8');
        $this->mailer = $mailer;
    }

    public function send(&$failedRecipients = null)
    {
        return $this->mailer->send($this, $failedRecipients);
    }
}
