<?php

namespace App\Controller;


use App\Entity\Tache;
use App\Form\TaskType;
use App\Entity\Projet;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskNewController extends AbstractController
{
    #[Route('/projets/nouvelleTache', name: 'app_newtask')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $projetid = $request->get('projetid');
        
        $em = $managerRegistry->getManager();

        $projet = $em->getRepository(Projet::class)->find($projetid);

        $tache = new Tache;

        $form = $this->createForm(TaskType::class, $tache);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $tache = $form->getData();
            $em->persist($tache);
            $em->flush();
            $tache->setProjet($projet);
            $em->flush();
            return $this->redirectToRoute('app_projets');
        }

        return $this->renderForm('projet/nouveauprojet.html.twig', [
            'form' => $form
        ]);
    }
}
