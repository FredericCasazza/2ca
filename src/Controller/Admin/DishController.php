<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Form\DishRemoveType;
use App\Form\DishType;
use App\Manager\DishManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DishController
 * @package App\Controller\Admin
 */
class DishController extends AbstractController
{

    /**
     * @Route("/admin/dish/{id}/ajax_edit", name="admin_dish_ajax_edit")
     * @param $id
     * @param Request $request
     * @param DishManager $dishManager
     * @return Response
     */
    public function ajaxEdit($id, Request $request, DishManager $dishManager)
    {
        $dish = $dishManager->find($id);

        if(!$dish instanceof Dish)
        {
            return $this->json([
                'status' => false,
                'content' => "Le menu {$id} n'existe pas"
            ]);
        }

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dish = $form->getData();
            $dishManager->update($dish);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/meal/edit_dish.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);
    }

    /**
     * @Route("/admin/dish/{id}/ajax_remove", name="admin_dish_ajax_remove")
     * @param $id
     * @param Request $request
     * @param DishManager $dishManager
     * @return Response
     * @throws \Exception
     */
    public function ajaxRemove($id, Request $request, DishManager $dishManager)
    {
        $dish = $dishManager->find($id);

        if(!$dish instanceof Dish)
        {
            return $this->json([
                'status' => false,
                'content' => "Le plat {$id} n'existe pas"
            ]);
        }

        $form = $this->createForm(DishRemoveType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Dish $dish */
            $dish = $form->getData();
            $dishManager->remove($dish);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);
        }

        $render = $this->get('twig')->render('admin/catalog/meal/remove_dish.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);

    }
}