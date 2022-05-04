<?php

namespace App\Controller;

use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ProjectController extends AbstractController
{
    #[Route('/projets', name: 'app_projets')]
    public function index(ProjetRepository $projetRepository, Request $request): Response
    {   
        $confirmDelete = $request->get('confirmDelete');
        $checkError = $request->get('checkError');

        # Dans le cadre d'un Undo de la suppression d'un projet, on récupère les anciennes valeurs pour recréer le projet sous un autre ID
        $oldLibelle = $request->get('oldlibelle');
        $oldDateDebut = $request->get('olddatedebut');
        $oldDateFin = $request->get('olddatefin');
        $oldBudget = $request->get('oldbudget');
        $oldCouts = $request->get('oldcouts');
        $undoLibelle = $request->get('undolibelle');
        # Variable à intégrer dès que l'insertion d'utilisateurs à un projet fonctionne
        # $oldusers = $request->get('oldattribution'); 

        $projets = $projetRepository->findAll();
        
        $typeutilisateur = 'gestionnaire';
        return $this->render('projet/projet.html.twig', [
            'typeutilisateur' => $typeutilisateur,
            'projets' => $projets,
            'confirmDelete' => $confirmDelete,
            'checkError' => $checkError,
            'libelle' => $oldLibelle,
            'datedebut' => $oldDateDebut,
            'datefin' => $oldDateFin,
            'budget' => $oldBudget,
            'couts' => $oldCouts,
            # 'oldusers' => $oldusers,
            'undolibelle' => $undoLibelle
        ]);
    }

    #[Route('/projets/modifier', name: 'app_editproject')]
    public function EditProject(Request $request, ManagerRegistry $managerRegistry): Response
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

    #[Route('/projets/nouveauProjet', name: 'app_newproject')]
    public function NewProject(Request $request, ManagerRegistry $managerRegistry): Response
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

    #[Route('/projet/supprimerProjet', name: 'app_removeproject')]
    public function DeleteProject(Request $request, ManagerRegistry $managerRegistry): Response
    {
        try 
        {
        $projetid = $request->get('projetid');

        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);

        $libelle = $projet->getLibelle();
        $dateDebut = $projet->getDatedebut();
        $dateFin = $projet->getDatefin();
        $budget = $projet->getBudget();
        $couts = $projet->getCouts();

        $em->remove($projet);
        $em->flush();

        return $this->redirectToRoute('app_projets', 
        [
            'confirmDelete' => 'le projet a bien été supprimé', 
            'oldlibelle' => $libelle, 
            'olddatedebut' => $dateDebut, 
            'olddatefin' => $dateFin, 
            'oldbudget' => $budget,
            'oldcouts' => $couts
        ]);
        }

        catch(ForeignKeyConstraintViolationException $e)

        {
            return $this->redirectToRoute('app_projets' , ['confirmDelete'=> 'Il reste des taches associés au projet', 'checkError' => $e]);
        }
        
    }

    #[Route('/projets/undo', name: 'app_undoproject')]
    public function UndoProject(Request $request, ManagerRegistry $managerRegistry): Response
    {

        $em = $managerRegistry->getManager();
        $libelle = $request->get('libelle');
        $dateDebut = new \DateTime($request->get('datedebut'));
        $dateFin = new \DateTime($request->get('datefin'));
        $budget = $request->get('budget');
        $couts = $request->get('couts');
        /* dd($libelle, $dateDebut, $dateFin, $budget, $couts); */

        $projet = new Projet;
        $projet->setLibelle($libelle)
        ->setDatedebut($dateDebut)
        ->setDatefin($dateFin)
        ->setBudget($budget)
        ->setCouts($couts);

        $em->persist($projet);
        $em->flush();


        return $this->redirectToRoute('app_projets', ['undolibelle' => $libelle]);
    }

    
}