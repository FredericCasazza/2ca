<?php


namespace App\Controller\Admin;


use App\Entity\Dish;
use App\Form\DishRemoveType;
use App\Manager\DishManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DishController extends AbstractController
{

    /**
     * @Route("/admin/dish/{id}/remove", name="admin_dish_remove")
     * @param $id
     * @param Request $request
     * @param DishManager $dishManager
     * @return Response
     * @throws \Exception
     */
    public function remove($id, Request $request, DishManager $dishManager)
    {
        $dish = $dishManager->find($id);

        if(!$dish instanceof Dish)
        {
            throw new \Exception("Le plat {$id} n'existe pas");
        }

        $form = $this->createForm(DishRemoveType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Dish $dish */
            $dish = $form->getData();
            $dishManager->remove($dish);

            return $this->json(true);
        }

        return $this->render('admin/meal/remove_dish.html.twig', [
            'form' => $form->createView()
        ]);

    }
}