<?php

namespace App\Controller;

use App\Entity\Projet;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UndoProjectController extends AbstractController
{
    #[Route('/projets/undo', name: 'app_undoproject')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
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
