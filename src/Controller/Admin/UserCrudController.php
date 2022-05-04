<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('roles')->allowMultipleChoices()->setChoices(['Administrateur' => 'ROLE_ADMIN', 'Utilisateur' => 'ROLE_USER', 'Gestionnaire' => 'ROLE_GESTION']),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('username')->setLabel('pseudo'),
            TextField::new('password')->setFormType(PasswordType::class)->setLabel('mot de passe'), 
            ChoiceField::new('settingtheme')->setLabel('Theme interface utilisateur')->setChoices(['Theme par defaut' => 'default_theme' ,'Dark Mode' => 'dark_theme']),
            ChoiceField::new('settinginterfacetype')->setLabel("Type interface utilisateur")->setChoices(['Vue par defaut' => 'default_view' ,'Vue complète' => 'complete_view'])
        ];
    }
}
