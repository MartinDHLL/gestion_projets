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
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class TaskController extends AbstractController
{
    #[Route('/projets/nouvelleTache', name: 'app_newtask')]
    public function NewTask(Request $request, ManagerRegistry $managerRegistry): Response
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

    #[Route('/projet/supprimerTache', name: 'app_removetask')]
    public function RemoveTask(Request $request, ManagerRegistry $managerRegistry): Response
    {
        try 
        {
        $tacheid = $request->get('tacheid');

        $em = $managerRegistry->getManager();
        $tache = $em->getRepository(Tache::class)->find($tacheid);
        $em->remove($tache);
        $em->flush();

        return $this->redirectToRoute('app_projets'/* , ['confirmDelete' => 'la tâche a bien été supprimé'] */);
        }

        catch(ForeignKeyConstraintViolationException $e)

        {
            return $this->redirectToRoute('app_projets' , ['confirmDelete'=> 'Il reste des utilisateurs associés à la tâche', 'checkError' => $e]);
        }
        
    }

    #[Route('/projet/modifierTache', name: 'app_edittask')]
    public function EditTask(Request $request, ManagerRegistry $managerRegistry): Response
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
