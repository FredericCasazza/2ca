<?php


namespace App\Controller\Site;


use App\Entity\Dish;
use App\Entity\Order;
use App\Manager\DishCategoryManager;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller\Site
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/booking/order/{id}/view", name="booking_order_view")
     * @param $id
     * @param OrderManager $orderManager
     * @param DishCategoryManager $dishCategoryManager
     * @return RedirectResponse|Response
     */
    public function order($id, OrderManager $orderManager, DishCategoryManager $dishCategoryManager)
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            $this->addFlash('danger', "La réservation {$id} n'existe pas");
            return $this->redirectToRoute('booking');
        }

        $dishesCategories = $dishCategoryManager->findEnableOrderedByPosition();

        $dishesByCategory = [];
        foreach ($dishesCategories as $dishCategory){
            $dishesByCategory[$dishCategory->getId()] = array_filter($order->getDishes()->toArray(), function (Dish $dish) use ($dishCategory){
                return $dish->getCategory()->getId() === $dishCategory->getId();
            });
        }

        return $this->render('site/order/view.html.twig', [
            'order' => $order,
            'dishesCategories' => $dishesCategories,
            'dishesByCategory' => $dishesByCategory
        ]);
    }





}