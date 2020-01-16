<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Entity\DishCategory;
use App\Form\DishCategoryDishType;
use App\Form\DishCategoryType;
use App\Form\Filter\DishCategoryFilterType;
use App\Form\RemoveType;
use App\Manager\DishCategoryManager;
use App\Manager\DishManager;
use App\Repository\DishCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DishCategoryController
 * @package App\Controller\Admin
 */
class DishCategoryController extends AbstractController
{
    /**
     * @Route("/admin/dish_category", name="admin_dish_categories")
     * @param Request $request
     * @param DishCategoryRepository $dishCategoryRepository
     * @param PaginatorInterface $paginator
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @return Response
     */
    public function list(
        Request $request,
        DishCategoryRepository $dishCategoryRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    )
    {
        $form = $this->createForm(DishCategoryFilterType::class);

        $qb = $dishCategoryRepository->createQueryBuilder('dc');
        $qb->addOrderBy('dc.position', 'ASC')
            ->addOrderBy('dc.label', 'ASC');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
        }

        $dishCategories = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/catalog/dish_category/list.html.twig', [
            'form' => $form->createView(),
            'dishCategories' => $dishCategories
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/view", name="admin_dish_category")
     * @param $id
     * @param DishCategoryManager $dishCategoryManager
     * @param DishManager $dishManager
     * @return RedirectResponse|Response
     */
    public function view($id, DishCategoryManager $dishCategoryManager,  DishManager $dishManager)
    {
        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            $this->addFlash('danger', "Le type de plat {$id} n'existe pas");
            return $this->redirectToRoute('admin_dish_categories');
        }

        return $this->render('admin/catalog/dish_category/view.html.twig', [
            'dishCategory' => $dishCategory
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/ajax_edit", name="admin_dish_category_ajax_edit")
     * @param $id
     * @param Request $request
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxEdit($id, Request $request, DishCategoryManager $dishCategoryManager)
    {
        $status = true;
        $message = null;

        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            return $this->json([
                'status' => false,
                'content' => "Le type de plat {$id} n'existe pas."
            ]);
        }

        $form = $this->createForm(DishCategoryType::class, $dishCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $dishCategory = $form->getData();
            $dishCategoryManager->update($dishCategory);

            //$this->addFlash('success', 'Modifications enregistrées avec succés.');

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/dish_category/edit.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/ajax_enable", name="admin_dish_category_ajax_enable")
     * @param $id
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxEnable($id, DishCategoryManager $dishCategoryManager)
    {
        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            return $this->json([
                'status' => false,
                'content' => "Le type de plat {$id} n'existe pas."
            ]);
        }

        $dishCategoryManager->enable($dishCategory);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/ajax_disable", name="admin_dish_category_ajax_disable")
     * @param $id
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxDisable($id, DishCategoryManager $dishCategoryManager)
    {
        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            return $this->json([
                'status' => false,
                'content' => "Le type de plat {$id} n'existe pas."
            ]);
        }

        $dishCategoryManager->disable($dishCategory);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/dish_category/ajax_create", name="admin_dish_category_ajax_create")
     * @param Request $request
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxCreate(Request $request, DishCategoryManager $dishCategoryManager)
    {
        $status = true;
        $message = null;

        $dishCategory = new DishCategory();

        $form = $this->createForm(DishCategoryType::class, $dishCategory);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $dishCategory = $form->getData();
                $dishCategoryManager->create($dishCategory);
            }else{
                $status = false;
                $message = ['type' => 'none'];
            }
        }

        $render = $this->get('twig')->render('admin/catalog/dish_category/create.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => $status,
            'content' => $render,
            'message' => $message
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/dish/ajax_add", name="admin_dish_category_dish_ajax_add")
     * @param $id
     * @param Request $request
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxAddDish($id, Request $request, DishCategoryManager $dishCategoryManager)
    {
        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            return $this->json([
                'status' => false,
                'content' => "Le type de plat {$id} n'existe pas"
            ]);
        }

        $form = $this->createForm(DishCategoryDishType::class, $dishCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Dish $dish */
            $dish = $form->get('dish')->getData();
            $dishCategory->addDish($dish);
            $dishCategoryManager->update($dishCategory);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/dish_category/add_dish.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/admin/dish_category/{id}/dish/{dish}/ajax_remove", name="admin_dish_category_dish_ajax_remove")
     * @param $id
     * @param $dish
     * @param Request $request
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function ajaxRemoveDish($id, $dish, Request $request, DishCategoryManager $dishCategoryManager)
    {
        $dishCategory = $dishCategoryManager->find($id);

        if(!$dishCategory instanceof DishCategory)
        {
            return $this->json([
                'status' => false,
                'content' => "Le type de plat {$id} n'existe pas"
            ]);
        }

        $form = $this->createForm(RemoveType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dishCategory->removeDish($dish);
            $dishCategoryManager->update($dishCategory);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/dish_category/remove_dish.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('admin_dish_category_dish_ajax_remove', [
                'id' => $id,
                'dish' => $dish
            ])
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }
}