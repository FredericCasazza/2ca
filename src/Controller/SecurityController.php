<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ReinitializationStartType;
use App\Form\ReinitPasswordType;
use App\Helper\ConfigurationHelper;
use App\Manager\UserManager;
use App\Specification\User\IsExpiredReinitTokenUserSpecification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
                if(!$this->checkCaptcha($request->request->get('_recaptcha_token'), $configurationHelper->getParameter('recaptchaSecretKey')))
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
     * @Route("/reinitialization/start", name="app_reinit_start")
     * @param Request $request
     * @param UserManager $userManager
     * @param ConfigurationHelper $configurationHelper
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function reinitStart(Request $request, UserManager $userManager, ConfigurationHelper $configurationHelper)
    {
        if($this->getUser() instanceof User)
        {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ReinitializationStartType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Check recaptcha token if recaptcha is enable
            if($configurationHelper->getParameter('recaptchaEnable'))
            {
                if(!$this->checkCaptcha($request->request->get('_recaptcha_token'), $configurationHelper->getParameter('recaptchaSecretKey')))
                {
                    $form->addError(new FormError("Le captcha n'est pas valide, veuillez réessayer"));
                    return $this->render('security/reinitialization_start.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }

            $email = $form->get('email')->getData();
            $user = $userManager->findByEmail($email);

            if(!$user instanceof User)
            {
                $this->addFlash('danger', "Aucun compte ne correspond à cette adresse email");
            }else{
                $userManager->createLostPasswordToken($user);
                return $this->render('security/reinitialization_start_success.html.twig');
            }
        }

        return $this->render('security/reinitialization_start.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reinitialization/password/{token}", name="app_reinit_password")
     * @param $token
     * @param Request $request
     * @param UserManager $userManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param IsExpiredReinitTokenUserSpecification $expiredReinitTokenUserSpecification
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function reinitPassword(
        $token,
        Request $request,
        UserManager $userManager,
        IsExpiredReinitTokenUserSpecification $expiredReinitTokenUserSpecification
    )
    {

        $user = $userManager->findByReinitToken($token);

        if(!$user instanceof User)
        {
            $this->addFlash('danger', "Le lien de réinitialisation n'est pas valide.");
            return $this->redirectToRoute('app_reinit_start');
        }

        if($expiredReinitTokenUserSpecification->isSatisfiedBy($user))
        {
            $this->addFlash('danger', "Le lien de réinitialisation a expiré.");
            $user->setReinitToken(null)
                ->setReinitExpirationDate(null);
            $userManager->update($user);
            return $this->redirectToRoute('app_reinit_start');
        }

        $form = $this->createForm(ReinitPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $userManager->update($user);

            return $this->render('security/reinitialization_password_success.html.twig');
        }

        return $this->render('security/reinitialization_password.html.twig', [
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

    /**
     * @param $recaptchaToken
     * @param $recaptchaSecretKey
     * @return bool
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function checkCaptcha($recaptchaToken, $recaptchaSecretKey)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $recaptchaSecretKey,
                'response' => $recaptchaToken
            ]
        ]);
        $content = $response->toArray();
        return ($response->getStatusCode() === 200 && array_key_exists('success', $content) && $content['success'] === true);
    }
}
