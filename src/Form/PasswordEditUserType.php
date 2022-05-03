<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordEditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', IntegerType::class, ['label' => "Définissez l'utilisateur avec son ID"])
            ->add('Submit',SubmitType::class, ['label' => 'Hash le mot de passe de cet utilisateur'])
            ->getForm()
        ;
    }
}
