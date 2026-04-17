<?php

/**
 * Formulaire Symfony lié à l'entité Player.
 * Ce fichier décrit les champs HTML, leurs labels et les règles de validation.
 * Le contrôleur fait : createForm(PlayerType::class, $player) pour afficher / enregistrer les données.
 */
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
    /**
     * Construit les champs du formulaire : nom du joueur, prénom, âge, bouton envoyer.
     * Les noms ('name', 'username', 'age') doivent correspondre aux propriétés de l'entité Player.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ texte mappé sur Player::$name
            ->add('name', TextType::class, [
                'label' => 'Nom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'maxlength' => 255
                ],
                // Règles appliquées à la soumission (côté serveur)
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(min: 3 , max: 255)
                ]
            ])
            // Champ texte mappé sur Player::$username (ici label "Prénom")
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
            // Nombre entier mappé sur Player::$age
            // [ALERTE] Length sert aux chaînes ; pour un âge, Range (min/max) est plus adapté qu'une "longueur".
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
            // Bouton d'envoi : ne correspond à aucune propriété d'entité, Symfony le gère seul
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btnForm'
                ]
            ]);
    }

    /**
     * Indique à Symfony quel objet PHP remplit le formulaire (hydratation des champs).
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Toutes les données du formulaire vont dans une instance de Player
            'data_class' => Player::class,
        ]);
    }
}
