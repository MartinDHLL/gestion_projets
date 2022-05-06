<?php

namespace App\Controller\Admin;

use App\Entity\MessageSignalementAdmin;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MessageSignalementAdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MessageSignalementAdmin::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('type')->setChoices(['projet' => 'projet', 'tache' => 'tache', 'soustache' => 'soustache', 'compte utilisateur (identifiant, mot de passe)' => 'compte utilisateur']),
            AssociationField::new('user'),
            TextField::new('titre'),
            TextEditorField::new('message'),
        ];
    }
    
}
