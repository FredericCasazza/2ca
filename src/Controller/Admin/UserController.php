<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Entity\User;
use App\Form\DishRemoveType;
use App\Form\Filter\UserFilterType;
use App\Form\UserType;
use App\Manager\DishManager;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{

    /**
     * @Route("/admin/user", name="admin_users")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @return Response
     */
    public function list(
        Request $request,
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    )
    {

        $form = $this->createForm(UserFilterType::class);

        $qb = $userRepository->createQueryBuilder('u')
            ->addOrderBy('u.lastname', 'asc')
            ->addOrderBy('u.firstname', 'asc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
        }

        $users = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/administration/user/list.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * @param $id
     * @param Request $request
     * @param UserManager $userManager
     * @return Response|RedirectResponse
     */
    public function edit($id, Request $request, UserManager $userManager)
    {
        $user = $userManager->find($id);

        if(!$user instanceof User)
        {
            $this->addFlash('danger', "L'utilisateur {$id} n'existe pas");
            return $this->redirectToRoute('admin_users');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var User $user */
            $user = $form->getData();
            $user->setRoles(array_values($user->getRoles()));
            $userManager->update($user);

            $this->addFlash('success', "Modifications enregistrées");
            return $this->redirectToRoute('admin_user_edit', [
                'id' => $id
            ]);
        }

        return $this->render('admin/administration/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}