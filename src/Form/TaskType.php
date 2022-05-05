<?php

namespace App\Form;

use App\Entity\Tache;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('datedebut', DateType::class, ['label' => 'date de début'])
            ->add('datefin', DateType::class, ['label' => 'date de fin'])
            ->add('statut', ChoiceType::class, 
                ['label' => "Statut : ",
                'choices' => 
                    [
                        'à faire' => 'à faire',
                        'en cours' => 'en cours',
                        'terminé' => 'terminé'
                    ]
                ])
            ->add('submit',SubmitType::class)
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
