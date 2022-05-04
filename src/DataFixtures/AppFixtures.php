<?php

namespace App\DataFixtures;

use App\Entity\Projet;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager): void
    {
        
        for($i=1; $i < 11; $i++)
        {
        $projet = new Projet();
        $projet->setLibelle('projet' .$i);
        $projet->setDatedebut(new \DateTime('now'));
        $manager->persist($projet);
        $manager->flush();
        }
        
        # Utilisateur Admin
        $userAdmin = new User();
        $userAdmin->setRoles(['ROLE_ADMIN'])
        ->setNom('admin')
        ->setPrenom('admin')
        ->setUsername('admin')
        ->setPassword($this->hash->hashPassword($userAdmin,'admin')) # Password : admin
        ->setSettinginterfacetype('default_view')
        ->setSettingtheme('default_theme');
        $manager->persist($userAdmin);
        $manager->flush();

        # Utilisateurs Gestionnaires
        for($i = 0; $i < 5; $i++)
        {
            $userGestionnaire = new User();
            $userGestionnaire->setRoles(['ROLE_GESTION'])
            ->setNom('nomGestionnaire'.$i)
            ->setPrenom('prenomGestionnaire'.$i)
            ->setUsername('gestionnaire'.$i)
            ->setPassword($this->hash->hashPassword($userGestionnaire,$i))
            ->setSettinginterfacetype('default_view')
            ->setSettingtheme('default_theme');
            $manager->persist($userGestionnaire);
            $manager->flush();
        }

        # Utilisateurs
        for($i = 0; $i < 20; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_USER'])
            ->setNom('nom'.$i)
            ->setPrenom('prenom'.$i)
            ->setUsername('utilisateur'.$i)
            ->setPassword($this->hash->hashPassword($user,$i))
            ->setSettinginterfacetype('default_view')
            ->setSettingtheme('default_theme');
            $manager->persist($user);
            $manager->flush();
        }    
    }
}
