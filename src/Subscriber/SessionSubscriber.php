<?php


namespace App\Subscriber;


use App\Factory\HelperFactory;
use App\Helper\ConfigurationHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SessionSubscriber
 * @package App\Subscriber
 */
class SessionSubscriber implements EventSubscriberInterface
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var HelperFactory
     */
    private $helperFactory;

    /**
     * SessionSubscriber constructor.
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     * @param HelperFactory $helperFactory
     */
    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        HelperFactory $helperFactory
    )
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->helperFactory = $helperFactory;
    }

    /**
     * @return array|void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['idleSession']
            ]
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function idleSession(RequestEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        /** @var ConfigurationHelper $configurationHelper */
        $configurationHelper = $this->helperFactory->get('configuration');
        $maxIdleTime = $configurationHelper->getParameter('sessionMaxIdleTime');

        if ($maxIdleTime > 0) {
            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();
            if ($lapse > $maxIdleTime && null !== $this->tokenStorage->getToken()) {
                $this->tokenStorage->setToken(null);
                // Change the route if you are not using FOSUserBundle.
                $event->setResponse(new RedirectResponse($this->router->generate('app_login')));
            }
        }
    }
}