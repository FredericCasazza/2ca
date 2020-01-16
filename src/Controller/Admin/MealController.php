<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Entity\Meal;
use App\Form\DishType;
use App\Form\Filter\MealFilterType;
use App\Form\MealEditType;
use App\Form\MealType;
use App\Manager\DishCategoryManager;
use App\Manager\DishManager;
use App\Manager\MealManager;
use App\Repository\MealRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MealController
 * @package App\Controller\Admin
 */
class MealController extends AbstractController
{

    /**
     * @Route("/admin/meal", name="admin_meals")
     * @param Request $request
     * @param MealRepository $mealRepository
     * @param PaginatorInterface $paginator
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @return Response
     */
    public function list(
        Request $request,
        MealRepository $mealRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    )
    {
        $form = $this->createForm(MealFilterType::class);

        $qb = $mealRepository->createQueryBuilder('m')
            ->innerJoin('m.period', 'p')
            ->addOrderBy('m.date', 'desc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
        }

        $meals = $paginator->paginate($qb, $request->query->getInt('page', 1));

        return $this->render('admin/catalog/meal/list.html.twig', [
            'form' => $form->createView(),
            'meals' => $meals
        ]);
    }

    /**
     * @Route("/admin/meal/{id}/view", name="admin_meal")
     * @param $id
     * @param MealManager $mealManager
     * @param DishCategoryManager $dishCategoryManager
     * @param DishManager $dishManager
     * @return Response|RedirectResponse
     */
    public function view($id, MealManager $mealManager, DishCategoryManager $dishCategoryManager, DishManager $dishManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            $this->addFlash('danger', "Le menu {$id} n'existe pas");
            return $this->redirectToRoute('admin_meals');
        }

        $dishesByCategories = [];
        foreach($dishCategoryManager->findEnableOrderedByPosition() as $category)
        {
            $dishesByCategories[$category->getLabel()] = $dishManager->findByMealAndDishCategory($meal, $category);
        }

        return $this->render('admin/catalog/meal/view.html.twig', [
            'meal' => $meal,
            'dishesByCategories' => $dishesByCategories
        ]);
    }

    /**
     * @Route("/admin/meal/{id}/ajax_publish", name="admin_meal_ajax_publish")
     * @param $id
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxPublish($id, MealManager $mealManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            return $this->json([
                'status' => false,
                'content' => "Le menu {$id} n'existe pas"
            ]);
        }

        $mealManager->publish($meal);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/meal/{id}/ajax_unpublish", name="admin_meal_ajax_unpublish")
     * @param $id
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxUnpublish($id, MealManager $mealManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            return $this->json([
                'status' => false,
                'content' => "Le menu {$id} n'existe pas"
            ]);
        }

        $mealManager->unpublish($meal);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/meal/ajax_create", name="admin_meal_ajax_create")
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxCreate(Request $request, MealManager $mealManager)
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $meal = $form->getData();
            $mealManager->create($meal);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);

        }

        $render = $this->get('twig')->render('admin/catalog/meal/create.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);

    }

    /**
     * @Route("/admin/meal/{id}/ajax_edit", name="admin_meal_ajax_edit")
     * @param $id
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxEdit($id, Request $request, MealManager $mealManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            return $this->json([
                'status' => false,
                'content' => "Le menu {$id} n'existe pas"
            ]);
        }

        $form = $this->createForm(MealEditType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $meal = $form->getData();
            $mealManager->update($meal);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/meal/edit.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/admin/meal/{id}/dish/ajax_add", name="admin_meal_dish_ajax_add")
     * @param $id
     * @param Request $request
     * @param MealManager $mealManager
     * @param DishManager $dishManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxAddDish($id, Request $request, MealManager $mealManager, DishManager $dishManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            return $this->json([
                'status' => false,
                'content' => "Le menu {$id} n'existe pas"
            ]);
        }

        $dish = new Dish();
        $dish->setMeal($meal);

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Dish $dish */
            $dish = $form->getData();
            $dishManager->create($dish);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/meal/add_dish.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/admin/meal/{id}/ajax_duplicate", name="admin_meal_ajax_duplicate")
     * @param $id
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxDuplicate($id, Request $request, MealManager $mealManager)
    {
        $meal = $mealManager->find($id);
        $meal = clone $meal;
        $meal->setDate(new \DateTime());

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $meal = $form->getData();
            $mealManager->create($meal);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);

        }

        $render = $this->get('twig')->render('admin/catalog/meal/duplicate.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);

    }

}