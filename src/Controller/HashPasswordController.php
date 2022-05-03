<?php

namespace App\Controller;

use App\Form\PasswordEditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordController extends AbstractController
{
    #[Route('/cdsc55351sfsffsf58869', name: 'admin_userpasswordedit')]
    public function EditPassword(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $hash): Response
    {

        $em = $managerRegistry->getManager();

        $form = $this->createForm(PasswordEditUserType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $user = $em->getRepository(User::class)->find($form->getData('id'));

            if(!$user)
            {
                throw $this->createNotFoundException(
                    " cet id n'existe pas ! "
                );
            }

            $oldPassword = $user->getPassword();

            $password = $hash->hashPassword($user,$oldPassword);
            $user->setPassword($password);
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('hash_password/index.html.twig', [
            'form' => $form
        ]);
    }
}
