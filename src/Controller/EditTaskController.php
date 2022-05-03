<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditTaskController extends AbstractController
{
    #[Route('/projet/modifierTache', name: 'app_edittask')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {

        $tacheid = $request->get('tacheid');

        $em = $managerRegistry->getManager();

        $tache = $em->getRepository(Tache::class)->find($tacheid);
        
        if(!$tache)
        {
        throw $this->createNotFoundException(
            'Aucune tache trouvé pour '.$tacheid
        );
        }

        $form = $this->createForm(TaskType::class, $tache);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $tache->setLibelle($form->get('libelle')->getData())
            ->setDateDebut($form->get('datedebut')->getData())
            ->setDateFin($form->get('datefin')->getData())
            ->setStatut($form->get('statut')->getData())
            ;

            $em->flush();

            return $this->redirectToRoute('app_projets');
        }
        
        return $this->renderForm('edit_task/index.html.twig', [
            'form' => $form
        ]);
    }
}
