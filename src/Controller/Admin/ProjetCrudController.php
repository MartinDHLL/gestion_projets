<?php

namespace App\Controller\Admin;

use App\Entity\Projet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projet::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('libelle')->setLabel('libellé du projet'),
            AssociationField::new('users')->setLabel('participants au projet'),
            AssociationField::new('gestionnaires'),
            DateField::new('datedebut')->setLabel('date de début'),
            DateField::new('datefin')->setLabel('date de fin'),
            MoneyField::new('budget')->setCurrency('EUR'),
            MoneyField::new('couts')->setCurrency('EUR')
        ];
    }
    
}
