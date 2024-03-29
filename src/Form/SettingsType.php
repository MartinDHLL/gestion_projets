<?php

namespace App\Form;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('settingtheme', ChoiceType::class, ['label' => "Thème : ",
                'choices' => 
                    [
                        'Theme par defaut' => 'default_theme',
                        'Thème Sombre (Dark Mode)' => 'dark_theme'
                    ]
            ])
            ->add('settinginterfacetype', ChoiceType::class, ['label' => "Type interface : ", 
                'choices' => 
                    [
                    'Interface par defaut' => 'default_view',
                    'Interface complète' => 'complete_view'
                    ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
