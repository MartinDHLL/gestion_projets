<?php

namespace App\Controller;

use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}