<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchivesController extends AbstractController
{
    #[Route('/archives', name: 'app_archives')]
    public function index(): Response
    {
        return $this->render('archives/index.html.twig', [
            'controller_name' => 'ArchivesController',
        ]);
    }
}
