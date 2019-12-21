<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Entity\Meal;
use App\Form\DishType;
use App\Form\MealType;
use App\Manager\DishCategoryManager;
use App\Manager\DishManager;
use App\Manager\MealManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MealController extends AbstractController
{

    /**
     * @Route("/admin/catalog/meal", name="admin_catalog_meals")
     * @param MealManager $mealManager
     * @return Response
     */
    public function meals(Request $request, MealManager $mealManager)
    {
        $meals = $mealManager->paginate($request->query->getInt('page', 1), 10);

        return $this->render('admin/meal/list.html.twig', [
            'meals' => $meals
        ]);
    }

    /**
     * @Route("/admin/catalog/meal/create", name="admin_catalog_meal_create")
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     */
    public function create(Request $request, MealManager $mealManager)
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $meal = $form->getData();
            $mealManager->create($meal);
        }

        return $this->render('admin/meal/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/catalog/meal/{id}", name="admin_catalog_meal")
     * @param $id
     * @param MealManager $mealManager
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function view($id, MealManager $mealManager, DishCategoryManager $dishCategoryManager, DishManager $dishManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            $this->addFlash('danger', "Le menu {$id} n'existe pas");
            $this->redirectToRoute('admin_catalog_meals');
        }

        $dishesByCategories = [];
        foreach($dishCategoryManager->findEnableOrderedByPosition() as $category)
        {
            $dishesByCategories[$category->getLabel()] = $dishManager->findByMealAndDishCategory($meal, $category);
        }

        return $this->render('admin/meal/view.html.twig', [
            'meal' => $meal,
            'dishesByCategories' => $dishesByCategories
        ]);
    }

    /**
     * @Route("/admin/catalog/meal/{id}/edit", name="admin_catalog_meal_edit", options = { "expose" = true })
     * @param $id
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function edit($id, Request $request, MealManager $mealManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            throw new \Exception("Le menu {$id} n'existe pas");
        }

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $meal = $form->getData();
            $mealManager->update($meal);
        }

        return $this->render('admin/meal/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/catalog/meal/{id}/dish/add", name="admin_catalog_meal_add_dish")
     * @param $id
     * @param Request $request
     * @param MealManager $mealManager
     * @param DishManager $dishManager
     * @return Response
     * @throws \Exception
     */
    public function addDish($id, Request $request, MealManager $mealManager, DishManager $dishManager)
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            throw new \Exception("Le menu {$id} n'existe pas");
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
        }

        return $this->render('admin/meal/add_dish.html.twig', [
            'form' => $form->createView()
        ]);
    }

}