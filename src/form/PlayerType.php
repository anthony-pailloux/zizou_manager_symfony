<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Objet formulaire : il sait comment afficher et valider les données,
 * mais les valeurs sont stockées dans un objet Player passé au contrôleur (createForm).
 */
class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Chaque ->add('nomPropriété', ...) relie un champ HTML à une propriété de l'objet Player
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

            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'placeholder' => 'pas dequipe',
                "required" => false
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Indique le type d'objet PHP à remplir : ici une instance de Player
            'data_class' => Player::class,
        ]);
    }
}
