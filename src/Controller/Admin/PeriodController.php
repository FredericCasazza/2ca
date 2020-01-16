<?php


namespace App\Controller\Admin;


use App\Entity\Period;
use App\Form\Filter\PeriodFilterType;
use App\Form\PeriodType;
use App\Manager\PeriodManager;
use App\Repository\PeriodRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PeriodController
 * @package App\Controller\Admin
 */
class PeriodController extends AbstractController
{
    /**
     * @Route("/admin/period", name="admin_periods")
     * @param Request $request
     * @param PeriodRepository $periodRepository
     * @param PaginatorInterface $paginator
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @return Response
     */
    public function list(
        Request $request,
        PeriodRepository $periodRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    )
    {
        $form = $this->createForm(PeriodFilterType::class);

        $qb = $periodRepository->createQueryBuilder('p');
        $qb->addOrderBy('p.position', 'ASC')
            ->addOrderBy('p.label', 'ASC');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
        }

        $periods = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/catalog/period/list.html.twig', [
            'form' => $form->createView(),
            'periods' => $periods
        ]);

    }

    /**
     * @Route("/admin/period/{id}/edit", name="admin_period_edit")
     * @param $id
     * @param Request $request
     * @param PeriodManager $periodManager
     * @return Response
     */
    public function view($id, Request $request, PeriodManager $periodManager)
    {
        $period = $periodManager->find($id);

        if (!$period instanceof Period) {
            $this->addFlash('danger', "Le repas {$id} n'existe pas");
            return $this->redirectToRoute('admin_periods');
        }

        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $period = $form->getData();
            $periodManager->update($period);

            $this->addFlash('success', 'Modifications enregistrées avec succés.');
        }

        return $this->render('admin/catalog/period/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/period/{id}/ajax_enable", name="admin_period_ajax_enable")
     * @param $id
     * @param PeriodManager $periodManager
     * @return Response
     */
    public function ajaxEnable($id, PeriodManager $periodManager)
    {
        $period = $periodManager->find($id);

        if(!$period instanceof Period)
        {
            return $this->json([
                'status' => false,
                'content' => "Le repas {$id} n'existe pas"
            ]);
        }

        $periodManager->enable($period);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/period/{id}/ajax_disable", name="admin_period_ajax_disable")
     * @param $id
     * @param PeriodManager $periodManager
     * @return Response
     */
    public function ajaxDisable($id, PeriodManager $periodManager)
    {
        $period = $periodManager->find($id);

        if(!$period instanceof Period)
        {
            return $this->json([
                'status' => false,
                'content' => "Le repas {$id} n'existe pas"
            ]);
        }

        $periodManager->disable($period);

        return $this->json([
            'status' => true,
            'content' => null,
        ]);
    }

    /**
     * @Route("/admin/period/ajax_create", name="admin_period_ajax_create")
     * @param Request $request
     * @param PeriodManager $periodManager
     * @return Response
     */
    public function ajaxCreate(Request $request, PeriodManager $periodManager)
    {
        $period = new Period();

        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $period = $form->getData();
            $periodManager->create($period);

            return $this->json([
                'status' => true,
                'content' => null,
            ]);

        }

        $render = $this->get('twig')->render('admin/catalog/period/create.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'status' => true,
            'content' => $render,
        ]);

    }
}