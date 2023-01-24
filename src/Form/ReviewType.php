<?php

namespace App\Form;

use App\Entity\Review;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                "label" => "Votre pseudo",
                "attr" => [
                    "placeholder" => "Votre pseudo"
                ]
            ])
            ->add('email',EmailType::class,[
                "label" => "Votre email",
                "attr" => [
                    "placeholder" => "Votre email"
                ]
            ])
            ->add('content',TextareaType::class,[
                "label" => "Votre critique",
                "attr"=>[
                    "placeholder" => "Votre critique"
                ]
            ])
            ->add('rating',ChoiceType::class,[
                "choices" => [
                    "Excellent" => 5,
                    "Très bon" => 4,
                    "Bon" => 3,
                    "Bof" => 2,
                    "A éviter/C'est dla merde" => 1
                ],
                "label" => "Avis"
            ])
            ->add('reactions',ChoiceType::class,[
                "choices" => [
                    "Sourire" => "sourire",
                    "Pleurer" => "pleurer",
                    "Réfléchir" => "réfléchir",
                    "Dormir" => "dormir",
                    "Rêver" => "rêver"
                ],
                "label" => "Vos réactions *",
                // Multiple true correspond au fait qu'on peut renvoyer plusieurs valeurs
                "multiple" => true,
                // expanded va transformer le champs en checkboxes
                "expanded" => true,
                "help" => "* Vous pouvez cocher plusieurs réponses"
            ])
            ->add('watchedAt',DateType::class,[
                "label" => "Quand avez vous regardé le film ?",
                // widget single_text permet d'afficher l'input sans le bootstrap par defaut
                "widget" => "single_text",
                "input" => "datetime_immutable"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,

        ]);
    }
}
