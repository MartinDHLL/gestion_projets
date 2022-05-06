<?php

namespace App\Controller;


use App\Entity\Tache;
use App\Form\TaskType;
use App\Entity\Projet;
use App\Entity\SousTache;
use App\Entity\User;
use App\Form\SubTaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{
    #[Route('/projets/nouvelleTache', name: 'app_newtask')]
    public function NewTask(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    { 

        $projetid = $request->get('projetid');
        
        $em = $managerRegistry->getManager();

        $projet = $em->getRepository(Projet::class)->find($projetid);

        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();

        $tache = new Tache;

        $form = $this->createForm(TaskType::class, $tache);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $tache = $form->getData();
            $em->persist($tache);
            $em->flush();
            $tache->setProjet($projet);
            $em->flush();
            if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
        }

        return $this->renderForm('edit_task/index.html.twig', [
            'form' => $form,
            'projet' => $projetid
        ]);
    }

    #[Route('/projet/supprimerTache', name: 'app_removetask')]
    public function RemoveTask(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {
        try 
        {
        $projetid = $request->get('projetid');
        $tacheid = $request->get('tacheid');

        $em = $managerRegistry->getManager();
        $tache = $em->getRepository(Tache::class)->find($tacheid);
        $em->remove($tache);
        $em->flush();

        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();
        
        if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
        }

        catch(ForeignKeyConstraintViolationException $e)

        {
            return $this->redirectToRoute('app_projets' , ['confirmDelete'=> 'Il reste des utilisateurs associés à la tâche', 'checkError' => $e]);
        }
        
    }

    #[Route('/projet/modifierTache', name: 'app_edittask')]
    public function EditTask(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response
    {

        $tacheid = $request->get('tacheid');
        $projetid = $request->get('projetid');

        $em = $managerRegistry->getManager();

        $tache = $em->getRepository(Tache::class)->find($tacheid);

        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();
        
        if(!$tache)
        {
        throw $this->createNotFoundException(
            'Aucune tache trouvé pour '.$tacheid
        );
        }

        $form = $this->createForm(TaskType::class, $tache);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $tache->setLibelle($form->get('libelle')->getData())
            ->setDateDebut($form->get('datedebut')->getData())
            ->setDateFin($form->get('datefin')->getData())
            ->setStatut($form->get('statut')->getData())
            ;

            $em->flush();

            if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
        }
    
        
        return $this->renderForm('edit_task/index.html.twig', [
            'form' => $form,
            'projet' => $projetid
        ]);
    }

    #[Route('/projet/nouvelleSousTache', name: 'app_newsubtask')]
    public function MakeSubtask(Request $request, ManagerRegistry $managerRegistry, UserInterface $currentuser): Response 
    {   
        $em = $managerRegistry->getManager();
        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();

        $projetid = $request->get('projetid');
        $tacheid = $request->get('tacheid');

        $tache = $em->getRepository(Tache::class)->find($tacheid);

        $form = $this->createForm(SubTaskType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $soustache = $form->getData();
            $tache->addSoustache($soustache);
            $em->persist($soustache);
            $em->flush();
            $em->persist($tache);
            $em->flush();

            if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
        }

        return $this->renderForm('edit_task/index.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/projet/editerSousTache', name: 'app_editsubtask')]
    public function EditSubTask(Request $request, UserInterface $currentuser, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();

        $projetid = $request->get('projetid');

        $soustache = $em->getRepository(SousTache::class)->find($request->get('soustacheid'));
        
        $form = $this->createForm(SubTaskType::class, $soustache);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $soustache->setLibelle($form->get('libelle')->getData())->setDatedebut($form->get('datedebut')->getData());
            
            $em->persist($soustache);
            $em->flush();

            if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
        }

        return $this->renderForm('edit_task/index.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('projet/supprimerSousTache', name: 'app_removesubtask')]
    public function RemoveSubTask(Request $request, UserInterface $currentuser, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $usersetting = $em->getRepository(User::class)->find($currentuser)->getSettinginterfacetype();

        $projetid = $request->get('projetid');

        $soustache = $em->getRepository(SousTache::class)->find($request->get('soustacheid'));

        $em->remove($soustache);
        $em->flush();

        if($usersetting != 'default_view')
            {
                return $this->redirectToRoute('app_projets');
            }
            else
            {
                return $this->redirectToRoute('app_projectview', ['projetid' => $projetid]);
            }
    }
}
