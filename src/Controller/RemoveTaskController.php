<?php

namespace App\Controller;

use App\Entity\Tache;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RemoveTaskController extends AbstractController
{
    #[Route('/projet/supprimerTache', name: 'app_removetask')]
    public function index(Request $request, ManagerRegistry $managerRegistry): RedirectResponse
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
}
