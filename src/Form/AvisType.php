<?php

namespace App\Form;

use App\Entity\Avis;
use App\Entity\Utilisateur;
use App\Entity\Jeux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'pseudo',
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
                
            ->add('description', TextType::class, [
                'label' => 'Avis',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])

            ->add('note', IntegerType::class, [
                'label' => 'Note',
                'attr' => [
                    'class' => 'form-control',
                    'max' => 10
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
