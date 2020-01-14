<?php


namespace App\Controller\Site;


use App\Constant\Role;
use App\Entity\CustomerRequest;
use App\Entity\Dish;
use App\Entity\Establishment;
use App\Entity\Meal;
use App\Entity\Order;
use App\Entity\User;
use App\Form\CustomerRequestType;
use App\Helper\OrderHelper;
use App\Helper\RoleHelper;
use App\Manager\CustomerRequestManager;
use App\Manager\DishCategoryManager;
use App\Manager\DishManager;
use App\Manager\MealManager;
use App\Manager\OrderManager;
use App\Specification\Meal\IsBookableMealSpecification;
use App\Specification\Order\CanAddThisDishToOrderSpecification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 * @package App\Controller\Site
 */
class BookingController extends AbstractController
{

    /**
     * @Route("/booking/meals/{selectedDate}", name="booking", defaults={"selectedDate": null})
     * @param $selectedDate
     * @param MealManager $mealManager
     * @param OrderManager $orderManager
     * @param RoleHelper $roleHelper
     * @return Response
     * @throws \Exception
     */
    public function booking($selectedDate, MealManager $mealManager, OrderManager $orderManager, RoleHelper $roleHelper)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$roleHelper->isGranted($user, Role::ROLE_CLIENT) || !$user->getEstablishment() instanceof Establishment)
        {
            return $this->redirectToRoute('booking_not_customer');
        }


        // Init
        $dates = [];
        $current = (new \DateTime('today'))->modify('+1 day');
        $selectedDate = (preg_match('/^.{4}-.{2}-.{2}/', $selectedDate) !== 1)? $current : \DateTime::createFromFormat('Y-m-d H:i:s',"{$selectedDate} 00:00:00");
        if($selectedDate >= (clone $current)->modify('+15 days'))
        {
            $selectedDate = $current;
        }

        // Fill dates array with 15 next days
        for ($i=0; $i < 15; $i++)
        {
            $dates[$current->format('Y-m-d')] = $current;
            $date = clone $current;
            $date->modify('+1 day');
            $current = $date;
        }

        // Find meals to selectedDate
        $meals = $mealManager->findBookableByDateAndEstablishment($selectedDate, $user->getEstablishment());

        // Find user meals orders
        $orders = [];
        /** @var Meal $meal */
        foreach ($meals as $meal)
        {
            $order = $orderManager->findOneByUserAndMeal($user, $meal);
            if($order instanceof Order)
            {
                $orders[$meal->getId()] = $order;
            }
        }

        return $this->render('site/booking/main.html.twig', [
            'dates' => $dates,
            'selectedDate' => $selectedDate->format('Y-m-d'),
            'meals' => $meals,
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/booking/not_customer", name="booking_not_customer")
     * @param Request $request
     * @param RoleHelper $roleHelper
     * @param CustomerRequestManager $customerRequestManager
     * @return Response
     * @throws \Exception
     */
    public function notCustomer(Request $request, RoleHelper $roleHelper, CustomerRequestManager $customerRequestManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($roleHelper->isGranted($user, Role::ROLE_CLIENT) && $user->getEstablishment() instanceof Establishment)
        {
            return $this->redirectToRoute('booking');
        }

        $customerRequest = $customerRequestManager->findByUser($user);

        if($customerRequest instanceof CustomerRequest){
            // If customer request has more than 2 days
            if($customerRequest->getCreationDate() <= (new \DateTime())->modify('-2 day'))
            {
                $customerRequestManager->remove($customerRequest);
            }
            else
            {
                return $this->render('site/booking/not_customer.html.twig');
            }
        }

        $customerRequest = new CustomerRequest();
        $customerRequest->setUser($user);

        $form = $this->createForm(CustomerRequestType::class, $customerRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $customerRequest = $form->getData();
            $customerRequestManager->create($customerRequest);

            return $this->render('site/booking/customer_request_created.html.twig');
        }

        return $this->render('site/booking/not_customer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/meal/{id}", name="booking_meal")
     * @param $id
     * @param MealManager $mealManager
     * @param DishCategoryManager $dishCategoryManager
     * @param DishManager $dishManager
     * @param OrderManager $orderManager
     * @param OrderHelper $orderHelper
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function meal(
        $id,
        MealManager $mealManager,
        DishCategoryManager $dishCategoryManager,
        DishManager $dishManager,
        OrderManager $orderManager,
        OrderHelper $orderHelper
    )
    {
        $meal = $mealManager->find($id);

        if(!$meal instanceof Meal)
        {
            $this->addFlash('danger', "Le menu {$id} n'existe pas");
            return $this->redirectToRoute('booking');
        }

        $isBookableMealSpecification = new IsBookableMealSpecification();
        if(!$isBookableMealSpecification->isSatisfiedBy($meal))
        {
            $this->addFlash('danger', "Vous ne pouvez pas faire de réservation sur ce menu.");
            return $this->redirectToRoute('booking');
        }

        $dishesCategories = $dishCategoryManager->findEnableOrderedByPosition();

        $dishesByCategory = [];
        foreach ($dishesCategories as $dishCategory){
            $dishes = $dishManager->findByMealAndDishCategory($meal, $dishCategory);
            $dishesByCategory[$dishCategory->getId()] = $dishes;
        }

        /** @var User $user */
        $user = $this->getUser();

        $order = $orderManager->findOneByUserAndMeal($user, $meal);

        if(!$order instanceof Order)
        {
            $order = new Order();
            $order->setUser($user)
                ->setEstablishment($user->getEstablishment())
                ->setMeal($meal);
            $orderManager->create($order);
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            throw $this->createAccessDeniedException();
        }

        if($orderHelper->isValidate($order))
        {
            return $this->redirectToRoute('order_view', [
                'id' => $order->getId()
            ]);
        }

        return $this->render('site/booking/meal.html.twig', [
            'meal' => $meal,
            'order' => $order,
            'dishesCategories' => $dishesCategories,
            'dishesByCategory' => $dishesByCategory
        ]);
    }

    /**
     * @Route("/booking/dish/{id}/ajax_add", name="booking_dish_ajax_add")
     * @param $id
     * @param OrderManager $orderManager
     * @param DishManager $dishManager
     * @param OrderHelper $orderHelper
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxAddDish($id, OrderManager $orderManager, DishManager $dishManager, OrderHelper $orderHelper)
    {
        $dish = $dishManager->find($id);

        if(!$dish instanceof Dish)
        {
            return $this->json([
                'status' => false,
                'content' => "Le plat {$id} n'existe pas"
            ]);
        }

        $meal = $dish->getMeal();

        $isBookableMealSpecification = new IsBookableMealSpecification();
        if(!$isBookableMealSpecification->isSatisfiedBy($meal))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous ne pouvez pas faire de réservation sur ce menu."
            ]);
        }

        /** @var User $user */
        $user = $this->getUser();

        $order = $orderManager->findOneByUserAndMeal($user, $meal);

        if(!$order instanceof Order)
        {
            return $this->json([
                'status' => false,
                'content' => "Il n'y a pas de réservation vous concernant à cette date."
            ]);
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'êtes pas autorisé à modifier cette commande."
            ]);
        }

        $canAddThisDishToOrderSpecification = new CanAddThisDishToOrderSpecification($order);
        if (!$canAddThisDishToOrderSpecification->isSatisfiedBy($dish))
        {
            return $this->json([
                'status' => false,
                'content' => "Limite de \"{$dish->getCategory()->getLabel()}\" autorisée déjà atteinte."
            ]);
        }

        $order->addDish($dish);

        $orderManager->update($order);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/booking/dish/{id}/ajax_remove", name="booking_dish_ajax_remove")
     * @param $id
     * @param OrderManager $orderManager
     * @param DishManager $dishManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxRemoveDish($id, OrderManager $orderManager, DishManager $dishManager, OrderHelper $orderHelper)
    {
        $dish = $dishManager->find($id);

        if(!$dish instanceof Dish)
        {
            return $this->json([
                'status' => false,
                'content' => "Le plat {$id} n'existe pas"
            ]);
        }

        $meal = $dish->getMeal();

        $isBookableMealSpecification = new IsBookableMealSpecification();
        if(!$isBookableMealSpecification->isSatisfiedBy($meal))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous ne pouvez pas faire de réservation sur ce menu."
            ]);
        }

        /** @var User $user */
        $user = $this->getUser();

        $order = $orderManager->findOneByUserAndMeal($user, $meal);

        if(!$order instanceof Order)
        {
            return $this->json([
                'status' => false,
                'content' => "Il n'y a pas de réservation vous concernant à cette dat."
            ]);
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'êtes pas autorisé à modifier cette commande."
            ]);
        }

        $order->removeDish($dish);

        $orderManager->update($order);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }


}