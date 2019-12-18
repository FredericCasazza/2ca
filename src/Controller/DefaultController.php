<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @return Response
     * @throws \Exception
     */
    public function number()
    {

        return $this->render('default/homepage.html.twig', [
        ]);
    }

}