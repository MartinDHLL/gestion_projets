<?php

namespace App\Controller;

use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProjectType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EditProjectController extends AbstractController
{
    #[Route('/projets/modifier', name: 'app_editproject')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $projetlibelle = $request->get('projetlibelle');
        $projetid = $request->get('projetid');

        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);

        if (!$projet) 
        {
            throw $this->createNotFoundException(
                'Aucun projet trouvé pour '.$projetid
            );
        }

        $form = $this->createForm(ProjectType::class, $projet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        { 
            /* dd($projet); */
            $projet->setLibelle($form->get('libelle')->getData())
            ->setDateDebut($form->get('datedebut')->getData())
            ->setDateFin($form->get('datefin')->getData())
            ->setBudget($form->get('budget')->getData())
            ->setCouts($form->get('couts')->getData())
            ;
            $em->flush();
            return $this->redirectToRoute('app_projets');
        }


        return $this->renderForm('projet/editproject.html.twig', [
            'projet' => $projetlibelle,
            'form' => $form,
            'projetid' => $projetid
        ]);

        
    }
}
