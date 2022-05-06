<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Entity\MessageSignalementAdmin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\User;
use App\Entity\Projet;
use App\Entity\Tache;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //


            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        

        
        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App Gestion Projet');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute("Aller sur l'app",'fas fa-home','app_redirect');
        yield MenuItem::linkToCrud('Gérer utilisateurs ', 'fas fa-list', User::class);
        yield MenuItem::linkToRoute('Hash mots de passe', 'fa-solid fa-key', 'admin_userpasswordedit');
        yield MenuItem::linkToCrud('Gérer projets', 'fas fa-list', Projet::class);
        yield MenuItem::linkToCrud('Gérer taches', 'fas fa-list', Tache::class);
        yield MenuItem::linkToCrud('Messages Signalement utilisateurs', 'fa-solid fa-comment', MessageSignalementAdmin::class);
        yield MenuItem::linkToCrud('Modérer messages projets', 'fa-solid fa-comment', Message::class);
    }
}
