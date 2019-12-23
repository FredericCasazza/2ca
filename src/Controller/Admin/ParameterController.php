<?php


namespace App\Controller\Admin;


use App\Form\ParameterType;
use App\Helper\ConfigurationHelper;
use App\Manager\ConfigurationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ParameterController
 * @package App\Controller\Admin
 */
class ParameterController extends AbstractController
{

    /**
     * @Route("/admin/parameter/edit", name="admin_parameter_edit")
     * @param Request $request
     * @param ConfigurationManager $configurationManager
     * @param ConfigurationHelper $configurationHelper
     * @return Response
     */
    public function edit(Request $request, ConfigurationManager $configurationManager, ConfigurationHelper $configurationHelper)
    {
        $configuration = $configurationHelper->getConfiguration();
        $form = $this->createForm(ParameterType::class, $configuration);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $configuration = $form->getData();
            $configurationManager->update($configuration);

            $this->addFlash('success', "Modifications enregistrées");
            $this->redirectToRoute('admin_parameter_edit');
        }

        return $this->render('admin/administration/parameter/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}