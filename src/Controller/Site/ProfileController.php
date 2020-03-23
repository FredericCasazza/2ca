<?php


namespace App\Controller\Site;


use App\Entity\User;
use App\Form\ProfilePasswordType;
use App\Form\ProfileRemoveType;
use App\Form\ProfileType;
use App\Form\UserRemoveType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class ProfileController
 * @package App\Controller\Site
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     */
    public function view()
    {
        return $this->render('site/profile/view.html.twig');
    }

    /**
     * @Route("/profile/ajax_edit", name="profile_ajax_edit")
     * @param Request $request
     * @param UserManager $userManager
     * @return JsonResponse
     */
    public function ajaxEdit(Request $request, UserManager $userManager)
    {
        $status = true;
        $message = null;

        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid()) {
                $user = $form->getData();
                $userManager->update($user);
            }else{
                $status = false;
                $message = ['type' => 'none'];
            }
        }

        $render = $this->get('twig')->render('site/profile/edit.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => $status,
            'content' => $render,
            'message' => $message
        ]);
    }

    /**
     * @Route("/profile/password", name="profile_ajax_password")
     * @param Request $request
     * @param UserManager $userManager
     * @return JsonResponse
     */
    public function ajaxPassword(Request $request, UserManager $userManager)
    {
        $status = true;
        $message = null;

        $user = $this->getUser();
        $form = $this->createForm(ProfilePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $user = $form->getData();
            $oldPassword = $form->get('oldPassword')->getData();

            if (!$userManager->checkPassword($user, $oldPassword)) {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe actuel est incorrect"));
            }

            if($form->isValid())
            {
                $userManager->update($user);
            }
            else
            {
                $status = false;
                $message = ['type' => 'none'];
            }
        }

        $render = $this->get('twig')->render('site/profile/password.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => $status,
            'content' => $render,
            'message' => $message
        ]);
    }

    /**
     * @Route("/profile}/ajax_remove", name="profile_ajax_remove")
     * @param Request $request
     * @param UserManager $userManager
     * @return JsonResponse
     */
    public function ajaxRemove(Request $request, UserManager $userManager, RequestStack $requestStack)
    {
        $status = true;
        $message = null;

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileRemoveType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $user = $form->getData();

            if(!$userManager->checkPassword($user, $user->getPlainTextPassword()))
            {
                $form->get('plainTextPassword')->addError(new FormError("Le mot de passe est incorrect"));
            }

            if($form->isValid())
            {
                $userManager->remove($user);
                $this->get('security.token_storage')->setToken(null);
            }
            else
            {
                $status = false;
                $message = ['type' => 'none'];
            }

        }

        $render = $this->get('twig')->render('site/profile/remove.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => $status,
            'content' => $render,
            'message' => $message
        ]);
    }
}