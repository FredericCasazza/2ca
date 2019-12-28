<?php


namespace App\Controller\Site;


use App\Manager\MealManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 * @package App\Controller\Site
 */
class BookingController extends AbstractController
{

    /**
     * @Route("/booking", name="booking")
     * @param Request $request
     * @param MealManager $mealManager
     * @return Response
     * @throws \Exception
     */
    public function booking(Request $request, MealManager $mealManager)
    {
        //$meals = $mealManager->paginateBookable($request->query->getInt('page', 1), 15);

        $dates = [];
        $current = new \DateTime();
        for ($i=0; $i < 15; $i++)
        {
            $dates[date_timestamp_get($current)] = $current;
            $date = clone $current;
            $date->modify('+1 day');
            $current = $date;
        }

        return $this->render('site/booking/main.html.twig', [
            'dates' => $dates
        ]);
    }

    /**
     * @Route("/booking/ajax_meals", name="booking_ajax_meals")
     * @param $timestamp
     * @param Request $request
     * @param MealManager $mealManager
     * @throws \Exception
     */
    public function bookingMeals($timestamp, Request $request, MealManager $mealManager)
    {
        $date = new \DateTime($timestamp);
        $meals = $mealManager->findBookableByDate($date);
    }
}