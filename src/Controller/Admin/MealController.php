<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MealController extends AbstractController
{

    /**
     * @Route("/admin/catalog/meal", name="admin_catalog_meals")
     * @return Response
     */
    public function meals()
    {
        return $this->render('admin/meal/list.html.twig');
    }

}