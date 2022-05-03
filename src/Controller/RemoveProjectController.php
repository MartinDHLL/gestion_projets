<?php

namespace App\Controller;

use App\Entity\Projet;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RemoveProjectController extends AbstractController
{
    #[Route('/projet/supprimerProjet', name: 'app_removeproject')]
    public function index(Request $request, ManagerRegistry $managerRegistry): RedirectResponse
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
}
