<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Helper\ConfigurationHelper;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/registration", name="app_registration")
     * @param Request $request
     * @param UserManager $userManager
     * @param ConfigurationHelper $configurationHelper
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function registration(Request $request, UserManager $userManager, ConfigurationHelper $configurationHelper)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Check recaptcha token if recaptcha is enable
            if($configurationHelper->getParameter('recaptchaEnable'))
            {
                $recaptchaToken = $request->request->get('_recaptcha_token');
                $client = HttpClient::create();
                $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret' => $configurationHelper->getParameter('recaptchaSecretKey'),
                        'response' => $recaptchaToken
                    ]
                ]);
                $content = $response->toArray();
                if($response->getStatusCode() !== 200 || !array_key_exists('success', $content) || $content['success'] !== true)
                {
                    $form->addError(new FormError("Le captcha n'est pas valide, veuillez réessayer"));
                    return $this->render('security/registration.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }

            $user = $form->getData();
            $userManager->create($user);

            return $this->render('security/registration_success.html.twig');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
