<?php

namespace App\Form;

use App\Entity\MessageSignalementAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, ['label' => 'Type de problème' ,'choices' => [ 'projet' => 'projet', 'tache' => 'tache', 'soustache' => 'soustache', 'compte utilisateur (identifiant, mot de passe)' => 'compte utilisateur' ]])
            ->add('titre')
            ->add('message')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MessageSignalementAdmin::class,
        ]);
    }
}
