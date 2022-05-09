<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageSignalementAdmin;
use App\Entity\Projet;
use App\Entity\User;
use App\Form\SignalementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagesController extends AbstractController
{
    #[Route('/signalementProbleme', name: 'sendproblemtoadmin')]
    public function SendSignalementMessageToAdmin(Request $request, UserInterface $currentuser, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $user = $em->getRepository(User::class)->find($currentuser);

        $form = $this->createForm(SignalementType::class);
        $form->handleRequest($request);

        $message = new MessageSignalementAdmin;

        if($form->isSubmitted() && $form->isValid())
        {  
            $message->setTitre($form->get('titre')->getData())
                    ->setMessage($form->get('message')->getData())
                    ->setType($form->get('type')->getData())
                    ->setUser($user);
            
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_projets');
        }

        return $this->renderForm('messages/sendadminmessage.html.twig', [
            'form' => $form
        ]);
    }
    
    
    #[Route('/projet/messagerie', name: 'app_messagerie')]
    public function showMessagerie(Request $request ,ManagerRegistry $managerRegistry): Response 
    {
        $projetid = $request->get('projetid');

        $em = $managerRegistry->getManager();
        $projet = $em->getRepository(Projet::class)->find($projetid);

        $messagesprojet = $em->getRepository(Message::class)->findBy(['projet' => $projet]);

        dd($messagesprojet);

        return $this->renderForm('messagerie.html.twig', [
            'messages' => $messagesprojet
        ]);
    }
    
}
