<?php

return array(
    'type'              => 'sendmail', //smtp, sendmail, mail, null
    'smtp_host'         => 'smtp.gmail.com',
    'smtp_port'         => 465,
    'smtp_username'     => '',
    'smtp_password'     => '',
    'smtp_security'     => 'ssl',
    'sendmail_command'  => '/usr/sbin/sendmail -bs',
    'mail_extra_params' => '-f%s'
);

