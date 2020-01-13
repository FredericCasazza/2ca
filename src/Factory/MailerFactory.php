<?php


namespace App\Factory;

use App\Helper\ConfigurationHelper;

/**
 * Class MailerFactory
 * @package App\Factory
 */
class MailerFactory
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    /**
     * MailerFactory constructor.
     * @param \Swift_Mailer $mailer
     * @param ConfigurationHelper $configurationHelper
     */
    public function __construct(\Swift_Mailer $mailer, ConfigurationHelper $configurationHelper)
    {
        $this->mailer = $mailer;
        $this->configurationHelper = $configurationHelper;
    }

    /**
     * @return \Swift_Mailer
     */
    public function create()
    {
        $host = $this->configurationHelper->getConfiguration()->getMailerHost();
        $port = $this->configurationHelper->getConfiguration()->getMailerPort();
        $encryption = $this->configurationHelper->getConfiguration()->getMailerEncryption();
        $authMode = $this->configurationHelper->getConfiguration()->getMailerAuthMode();
        $username = $this->configurationHelper->getConfiguration()->getMailerUsername();
        $password = $this->configurationHelper->getConfiguration()->getMailerPassword();
        $timeout = $this->configurationHelper->getConfiguration()->getMailerTimeout();

        $transport = new \Swift_SmtpTransport();
        if(!empty($host)) $transport->setHost($host);
        if(!empty($port)) $transport->setPort($port);
        if(!empty($encryption)) $transport->setEncryption($encryption);
        if(!empty($authMode)) $transport->setAuthMode($authMode);
        if(!empty($username)) $transport->setUsername($username);
        if(!empty($password)) $transport->setPassword($password);
        if(!empty($timeout)) $transport->setTimeout($timeout);

        return new \Swift_Mailer($transport);

    }
}