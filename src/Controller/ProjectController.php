<?php

namespace App\Controller;

use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Security\Core\User\UserInterface;


class ProjectController extends AbstractController
{
    #[Route('/projets', name: 'app_projets')]
    public function index(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {   
        $confirmDelete = $request->get('confirmDelete');
        $checkError = $request->get('checkError');

        $oldLibelle = $request->get('oldlibelle');
        $oldDateDebut = $request->get('olddatedebut');
        $oldDateFin = $request->get('olddatefin');
        $oldBudget = $request->get('oldbudget');
        $oldCouts = $request->get('oldcouts');
        $undoLibelle = $request->get('undolibelle');

        $em = $managerRegistry->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('username' => $currentuser->getUserIdentifier()));

        $projets = $user->getProjet();
        
        /* dd($projets); */
        
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

    #[Route('/projet/modifier', name: 'app_editproject')]
    public function EditProject(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $useredit = false;
        $useredittask = false;
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
            'projetid' => $projetid,
            'useredit' => $useredit,
            'useredittask' => $useredittask
        ]);
    }

    #[Route('/projet/nouveauProjet', name: 'app_newproject')]
    public function NewProject(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {
        $em = $managerRegistry->getManager();

        $projet = new Projet;
        $user = $em->getRepository(User::class)->findOneBy(array('username' => $currentuser->getUserIdentifier()));

        $form = $this->createForm(ProjectType::class, $projet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $projet = $form->getData();
            
            if($user->getRoles() != 'ROLE_USER')
            {
                $user->addProjet($projet);
                $em->persist($projet);
                $em->flush();
                $em->persist($user);
                $em->flush();
                $user->setProjetgestionnaire($projet);
                $em->persist($user);
                $em->flush();
            }
            return $this->redirectToRoute('app_projets');
        }

        return $this->renderForm('projet/nouveauprojet.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/projet/supprimerProjet', name: 'app_removeproject')]
    public function DeleteProject(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {
        try 
        {
        $projetid = $request->get('projetid');

        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);
        $user = $em->getRepository(User::class)->find($currentuser);

        $projet->removeGestionnaire($user);

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

    #[Route('/projet/undo', name: 'app_undoproject')]
    public function UndoProject(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {

        $em = $managerRegistry->getManager();
        $libelle = $request->get('libelle');
        $dateDebut = new \DateTime($request->get('datedebut'));
        $dateFin = new \DateTime($request->get('datefin'));
        $budget = $request->get('budget');
        $couts = $request->get('couts');
        $user = $em->getRepository(User::class)->find($currentuser);

        $projet = new Projet;
        $projet->setLibelle($libelle)
        ->setDatedebut($dateDebut)
        ->setDatefin($dateFin)
        ->setBudget($budget)
        ->setCouts($couts);
        $em->persist($projet);
        $em->flush();
        
        if($user->getRoles() != 'ROLE_USER')
            {
                $user->addProjet($projet);
                $em->persist($projet);
                $em->flush();
                $user->setProjetgestionnaire($projet);
                $em->persist($user);
                $em->flush();
            }


        return $this->redirectToRoute('app_projets', ['undolibelle' => $libelle]);
    }

    #[Route('/projet/taches', name: 'app_projectview')]
    public function ProjectView(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {
        $projetid = $request->get('projetid');
        
        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(projet::class)->find($projetid);
        return $this->render('/projet/projectview.html.twig', [
            'projet' => $projet
        ]);
    }

    #[Route('/projet/ajoutUtilisateur', name: 'app_projectadduser')]
    public function AddUser(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $projetid = $request->get('projetid');
        $useredit = true;
        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);

        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $projet->AddUser($form->get('users')->getData());
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projectadduser', ['projetid' => $projetid]);
        }

        return $this->renderForm('projet/editproject.html.twig', [
            'form' => $form,
            'projet' => $projet,
            'useredit' => $useredit,
            'useredittask' => false
        ]);
    }

    #[Route('projet/supprimerUtilisateur', name : 'app_projectremoveuser')]
    public function RemoveUser(Request $request, ManagerRegistry $manager, UserRepository $user, ProjetRepository $projetrepo, UserInterface $currentconnecteduser) : Response
    {
        $em = $manager->getManager();
        $userid = $request->get('user');
        $projetid = $request->get('projet');
        $checkuser = $user->find($currentconnecteduser);
        $projet = $projetrepo->find($projetid);

        $currentuser = $user->find($userid);
        
        if($checkuser == $currentuser || $currentuser->getRoles() == "ROLE_GESTION")
        {
            return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
        }
        else
        {
            $projet->removeUser($currentuser);
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
        }
        

    }
    
}