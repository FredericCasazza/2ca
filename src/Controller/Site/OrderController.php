<?php


namespace App\Controller\Site;


use App\Entity\Dish;
use App\Entity\Order;
use App\Form\OrderRemoveType;
use App\Form\ValidateType;
use App\Helper\OrderHelper;
use App\Manager\DishCategoryManager;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller\Site
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/order/{id}/view", name="order_view")
     * @param $id
     * @param OrderManager $orderManager
     * @param DishCategoryManager $dishCategoryManager
     * @param OrderHelper $orderHelper
     * @return RedirectResponse|Response
     */
    public function order(
        $id,
        OrderManager $orderManager,
        DishCategoryManager $dishCategoryManager,
        OrderHelper $orderHelper
    )
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            $this->addFlash('danger', "La réservation {$id} n'existe pas");
            return $this->redirectToRoute('booking');
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            throw $this->createAccessDeniedException();
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

    /**
     * @Route("/order/{id}/validate", name="order_ajax_validate")
     * @param $id
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderHelper $orderHelper
     * @return JsonResponse
     * @throws \Exception
     */
    public function validate(
        $id,
        Request $request,
        OrderManager $orderManager,
        OrderHelper $orderHelper
    )
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            return $this->json([
                'status' => false,
                'content' => "La commande {$id} n'existe pas."
            ]);
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'êtes pas autorisé à valider la commande {$id}."
            ]);
        }

        if($orderHelper->isValidate($order))
        {
            return $this->json([
                'status' => false,
                'content' => "La commande {$id} a déjà été validé."
            ]);
        }

        $form = $this->createForm(ValidateType::class, $order, ['data_class' => Order::class]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Order $order */
            $order = $form->getData();
            $orderManager->validate($order);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('site/order/validate.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/order/{id}/ajax_remove", name="order_ajax_remove")
     * @param $id
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderHelper $orderHelper
     * @return Response
     */
    public function ajaxRemove($id, Request $request, OrderManager $orderManager, OrderHelper $orderHelper)
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            return $this->json([
                'status' => false,
                'content' => "La commande {$id} n'existe pas."
            ]);
        }

        if(!$orderHelper->isAuthorizedUser($order))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'êtes pas autorisé à valider la commande {$id}."
            ]);
        }

        $form = $this->createForm(OrderRemoveType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Dish $dish */
            $order = $form->getData();
            $orderManager->remove($order);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('site/order/remove.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);

    }

}