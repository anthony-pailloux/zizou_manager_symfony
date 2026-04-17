<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(min: 3 , max: 255)
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Prénom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire'),
                    new Length(min: 3 , max: 255)
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Age',
                    'min' => 1,
                    'max' => 99
                ],
                'constraints' => [
                    new NotBlank(message: 'Age est obligatoire'),
                    new Length(min: 2 , max: 3)
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btnForm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
