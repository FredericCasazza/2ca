<?php


namespace App\Controller\Admin;


use App\Entity\Notification;
use App\Entity\User;
use App\Manager\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package App\Controller\Admin
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/admin/notification", name="admin_notifications")
     * @param Request $request
     * @param NotificationManager $notificationManager
     * @return Response
     */
    public function list(Request $request, NotificationManager $notificationManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        $notifications = $notificationManager->paginateByUser($user, $request->query->getInt('page', 1), 15);

        return $this->render('admin/notifications/list.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/admin/notification/{id}/ajax_check", name="admin_notification_ajax_check", options={"expose"=true})
     * @param $id
     * @param NotificationManager $notificationManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxCheck($id, NotificationManager $notificationManager)
    {

        $notification = $notificationManager->find($id);

        if(!$notification instanceof Notification)
        {
            return $this->json([
                'status' => false,
                'content' => "La notification {$id} n'existe pas"
            ]);
        }

        if($notification->getUser() !== $this->getUser()->getId() && !in_array($notification->getRole(), $this->getUser()->getRoles()))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'avez pas la permission de marquer comme lue la notification {$id}"
            ]);
        }

        $notificationManager->check($notification);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/notifications/{id}/ajax_delete", name="admin_notification_ajax_delete")
     * @param $id
     * @param NotificationManager $notificationManager
     * @return JsonResponse
     */
    public function ajaxDelete($id, NotificationManager $notificationManager)
    {
        $notification = $notificationManager->find($id);

        if(!$notification instanceof Notification)
        {
            return $this->json([
                'status' => false,
                'content' => "La notification {$id} n'existe pas"
            ]);
        }

        if($notification->getUser() !== $this->getUser()->getId() && !in_array($notification->getRole(), $this->getUser()->getRoles()))
        {
            return $this->json([
                'status' => false,
                'content' => "Vous n'avez pas la permission de supprimer la notification {$id}"
            ]);
        }

        $notificationManager->remove($notification);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }
}