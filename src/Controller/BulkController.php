<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BulkController
 * @package App\Controller
 */
class BulkController extends AbstractController
{
    /**
     * @Route("/bulk/action", name="bulk_action")
     * @return Response
     */
    public function action()
    {
        $render = $this->get('twig')->render('bulk_action.html.twig');
        return $this->json([
            'status' => true,
            'content' => $render
        ]);
    }
}