<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjectType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectNewController extends AbstractController
{
    #[Route('/projets/nouveauProjet', name: 'app_newproject')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        $projet = new Projet;

        $form = $this->createForm(ProjectType::class, $projet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $projet = $form->getData();
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projets');
        }

        return $this->renderForm('projet/nouveauprojet.html.twig', [
            'form' => $form
        ]);
    }
}
