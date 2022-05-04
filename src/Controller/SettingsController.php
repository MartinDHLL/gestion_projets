<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SettingsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function index(Request $request, ManagerRegistry $managerRegistry, UserInterface $userid): Response
    {
        $em = $managerRegistry->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('username' => $userid->getUserIdentifier()));

        $form = $this->createForm(SettingsType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setSettingtheme($form->get('settingtheme')->getData())
            ->setSettinginterfacetype($form->get('settinginterfacetype')->getData());
            $em->flush();
            return $this->redirectToRoute('app_projets');
        }

        return $this->renderForm('settings/index.html.twig', [
            'form' => $form
        ]);
    }
}
