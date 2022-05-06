<?php

namespace App\Controller\Admin;

use App\Entity\MessageSignalementAdmin;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MessageSignalementAdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MessageSignalementAdmin::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
