<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Projet;
use App\Repository\ArchiveRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function makeArchive(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $projetid = $request->get('projetid');
        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);

        $archive = new Archive;

        $archive->setLibelle($projet->getLibelle())
        ->setDatedebut($projet->getDateDebut())
        ->setDatefin($projet->getDateFin())
        ->setBudget($projet->getBudget())
        ->setCouts($projet->getCouts());

        $em->persist($archive);
        $em->flush();

        $em->remove($projet);
        
        return $this->redirectToRoute('app_projets');
    }
}
