<?php


namespace App\Controller\Site;


use App\Entity\Order;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function order($id, OrderManager $orderManager)
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            $this->addFlash('danger', "La réservation {$id} n'existe pas");
        }
    }





}