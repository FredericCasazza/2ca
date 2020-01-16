<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Entity\Order;
use App\Form\Filter\OrderFilterType;
use App\Form\PrintOrderType;
use App\Manager\DishCategoryManager;
use App\Manager\OrderManager;
use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller\Admin
 */
class OrderController extends AbstractController
{

    /**
     * @Route("/admin/order", name="admin_orders")
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param PaginatorInterface $paginator
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @return Response
     */
    public function list(
        Request $request,
        OrderRepository $orderRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    )
    {
        $form = $this->createForm(OrderFilterType::class);

        $qb = $orderRepository->createQueryBuilder('o')
            ->innerJoin('o.meal', 'm')
            ->addOrderBy('m.date', 'desc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->setParts([
                '__root__' => 'o',
                'o.meal' => 'm'
            ]);
            $filterBuilderUpdater->addFilterConditions($form, $qb);
        }

        $orders = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/order/list.html.twig', [
            'form' => $form->createView(),
            'orders' => $orders
        ]);

    }

    /**
     * @Route("/admin/order/{id}/view", name="admin_order")
     * @param $id
     * @param OrderManager $orderManager
     * @param DishCategoryManager $dishCategoryManager
     * @return Response
     */
    public function view($id, OrderManager $orderManager, DishCategoryManager $dishCategoryManager)
    {
        $order = $orderManager->find($id);

        if(!$order instanceof Order)
        {
            $this->addFlash('danger', "La réservation {$id} n'existe pas");
            return $this->redirectToRoute('admin_orders');
        }

        $dishesCategories = $dishCategoryManager->findEnableOrderedByPosition();

        $dishesByCategory = [];
        foreach ($dishesCategories as $dishCategory){
            $dishesByCategory[$dishCategory->getId()] = array_filter($order->getDishes()->toArray(), function (Dish $dish) use ($dishCategory){
                return $dish->getCategory()->getId() === $dishCategory->getId();
            });
        }

        return $this->render('admin/order/view.html.twig', [
            'order' => $order,
            'dishesCategories' => $dishesCategories,
            'dishesByCategory' => $dishesByCategory
        ]);
    }

    /**
     * @Route("/admin/order/print", name="admin_order_ajax_print")
     * @param Request $request
     * @param OrderManager $orderManager
     * @param DishCategoryManager $dishCategoryManager
     * @return JsonResponse|Response
     */
    public function ajaxPrint(Request $request, OrderManager $orderManager, DishCategoryManager $dishCategoryManager)
    {
        $form = $this->createForm(PrintOrderType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $date = $form->get('date')->getData();
            $period = $form->get('period')->getData();
            $establishment = $form->get('establishment')->getData();

            $orders = $orderManager->findValidByDatePeriodAndEstablishment($date, $period, $establishment);

            $dishCategories = $dishCategoryManager->findEnableOrderedByPosition();

            $ordersDishCategories = [];
            /** @var Order $order */
            foreach ($orders as $order)
            {
                /** @var Dish $dish */
                foreach ($order->getDishes() as $dish)
                {
                    $ordersDishCategories[$order->getId()][$dish->getCategory()->getId()][] = $dish;
                }
            }

            return $this->render('admin/order/print_list.html.twig', [
                'date' => $date,
                'period' => $period,
                'establishment' => $establishment,
                'orders' => $orders,
                'dishCategories' => $dishCategories,
                'ordersDishCategories' => $ordersDishCategories
            ]);
        }

        $render = $this->get('twig')->render('admin/order/print.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

}