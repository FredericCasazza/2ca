<?php


namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageController
 * @package App\Controller\Site
 */
class HomepageController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @return Response
     * @throws \Exception
     */
    public function number()
    {
        return $this->redirectToRoute('booking');

        return $this->render('site/homepage.html.twig', [
        ]);
    }

}