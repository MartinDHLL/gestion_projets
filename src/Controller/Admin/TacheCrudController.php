<?php

namespace App\Controller\Admin;

use App\Entity\Tache;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class TacheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tache::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('libelle')->setLabel('libellé'),
            AssociationField::new('projet')->setLabel(('projet associé'))->setRequired('projet'),
            AssociationField::new('users')->setLabel('participants à la tâche'),
            AssociationField::new('soustache')->setLabel('sous tache'),
            DateField::new('datedebut')->setLabel('date de début'),
            DateField::new('datefin')->setLabel('date de fin'),
            TextField::new('statut'),
            BooleanField::new('validation')
        ];
    }
    
}
