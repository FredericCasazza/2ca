<?php


namespace App\Subscriber;


use App\Event\User\CreateLostPasswordTokenUserEvent;
use App\Factory\MailerFactory;
use App\Helper\ConfigurationHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailerSubscriber
 * @package App\Subscriber
 */
class MailerSubscriber implements EventSubscriberInterface
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
     * @var Environment
     */
    private $twig;

    /**
     * MailerSubscriber constructor.
     * @param MailerFactory $mailerFactory
     * @param ConfigurationHelper $configurationHelper
     * @param Environment $twig
     */
    public function __construct(MailerFactory $mailerFactory, ConfigurationHelper $configurationHelper, Environment $twig)
    {
        $this->mailer = $mailerFactory->create();
        $this->configurationHelper = $configurationHelper;
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateLostPasswordTokenUserEvent::class => [
                ['createLostPasswordToken', 10]
            ]
        ];
    }

    /**
     * @return string|null
     */
    private function getMailerSubjectPrefix()
    {
        return $this->configurationHelper->getConfiguration()->getMailerSubjectPrefix();
    }

    /**
     * @return string|null
     */
    private function getMailerFrom()
    {
        return $this->configurationHelper->getConfiguration()->getMailerFrom();
    }

    /**
     * @param CreateLostPasswordTokenUserEvent $event
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createLostPasswordToken(CreateLostPasswordTokenUserEvent $event)
    {
        $user = $event->getUser();

        $subject = trim($this->getMailerSubjectPrefix()." Réinitialisation de votre mot de passe");
        $body = $this->twig->render('mail/reinitialize_password.html.twig', [
            'user' => $user
        ]);

        $message = new \Swift_Message();
        $message->setFrom($this->getMailerFrom())
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

}