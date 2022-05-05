<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Projet;
use App\Entity\User;
use App\Repository\ArchiveRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ArchivesController extends AbstractController
{
    #[Route('/archives', name: 'app_archives')]
    public function index(ArchiveRepository $archiveRepository): Response
    {
        $archives = $archiveRepository->findAll();

        return $this->render('archives/index.html.twig', [
            'archives' => $archives
        ]);
    }

    #[Route('/archives/make', name: 'app_makearchive')]
    public function makeArchive(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {
        $projetid = $request->get('projetid');
        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);
        $user = $em->getRepository(User::class)->find($currentuser);

        $archive = new Archive;

        $archive->setLibelle($projet->getLibelle())
        ->setDatedebut($projet->getDateDebut())
        ->setDatefin($projet->getDateFin())
        ->setBudget($projet->getBudget())
        ->setCouts($projet->getCouts());
        $projet->removeGestionnaire($user);

        try
        {
            $em->remove($projet);
            $em->flush();
        }
        catch(ForeignKeyConstraintViolationException $e)
        {
            return $this->redirectToRoute('app_projets' , ['confirmDelete'=> 'Il reste des taches associés au projet', 'checkError' => $e]);
        }

        $em->persist($archive);
        $em->flush();
        
        
        return $this->redirectToRoute('app_projets');
    }
}
