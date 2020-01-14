<?php


namespace App\Controller\Admin;


use App\Entity\Establishment;
use App\Entity\Period;
use App\Form\EstablishmentType;
use App\Form\PeriodType;
use App\Manager\EstablishmentManager;
use App\Manager\PeriodManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EstablishmentController
 * @package App\Controller\Admin
 */
class EstablishmentController extends AbstractController
{
    /**
     * @Route("/admin/establishment", name="admin_establishments")
     * @param Request $request
     * @param EstablishmentManager $establishmentManager
     * @return Response
     */
    public function list(Request $request, EstablishmentManager $establishmentManager)
    {
        $establishments = $establishmentManager->paginate($request->query->getInt('page', 1), 15);

        return $this->render('admin/catalog/establishment/list.html.twig', [
            'establishments' => $establishments
        ]);
    }

    /**
     * @Route("/admin/establishment/{id}/edit", name="admin_establishment_edit")
     * @param $id
     * @param Request $request
     * @param EstablishmentManager $establishmentManager
     * @return Response
     */
    public function view($id, Request $request, EstablishmentManager $establishmentManager)
    {
        $establishment = $establishmentManager->find($id);

        if (!$establishment instanceof Establishment) {
            $this->addFlash('danger', "L'établissement {$id} n'existe pas");
            return $this->redirectToRoute('admin_establishments');
        }

        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $establishment = $form->getData();
            $establishmentManager->update($establishment);

            $this->addFlash('success', 'Modifications enregistrées avec succés.');
        }

        return $this->render('admin/catalog/establishment/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/establihsment/{id}/ajax_enable", name="admin_establishment_ajax_enable")
     * @param $id
     * @param EstablishmentManager $establishmentManager
     * @return Response
     */
    public function ajaxEnable($id, EstablishmentManager $establishmentManager)
    {
        $establishment = $establishmentManager->find($id);

        if(!$establishment instanceof Establishment)
        {
            return $this->json([
                'status' => false,
                'content' => "L'établissement {$id} n'existe pas."
            ]);
        }

        $establishmentManager->enable($establishment);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/establishment/{id}/ajax_disable", name="admin_establishment_ajax_disable")
     * @param $id
     * @param EstablishmentManager $establishmentManager
     * @return Response
     */
    public function ajaxDisable($id, EstablishmentManager $establishmentManager)
    {
        $establishment = $establishmentManager->find($id);

        if(!$establishment instanceof Establishment)
        {
            return $this->json([
                'status' => false,
                'content' => "L'établissement {$id} n'existe pas"
            ]);
        }

        $establishmentManager->disable($establishment);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/establishment/ajax_create", name="admin_establishment_ajax_create")
     * @param Request $request
     * @param EstablishmentManager $establishmentManager
     * @return Response
     */
    public function ajaxCreate(Request $request, EstablishmentManager $establishmentManager)
    {
        $status = true;
        $message = null;

        $establishment = new Establishment();

        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $establishment = $form->getData();
                $establishmentManager->create($establishment);
            }else{
                $status = false;
                $message = ['type' => 'none'];
            }
        }

        $render = $this->get('twig')->render('admin/catalog/establishment/create.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => $status,
            'content' => $render,
            'message' => $message
        ]);
    }
}