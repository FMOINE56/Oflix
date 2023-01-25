<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                "label" => "Titre du film",
                "attr" => [
                    "placeholder" => "Titre du film"
                ]
            ])
            ->add('duration', IntegerType::class,[
                "label" => "Durée du film *",
                "attr" => [
                    "placeholder" => "Durée du film"
                ],
                "help" => "* Durée du film en minutes"
            ])
            ->add('releaseDate', DateType::class,[
                "label" => "Date de sortie du film",
                "widget" => "single_text"
            ])
            ->add('synopsis',TextareaType::class,[
                "label" => "Synopsis",
                "attr" => [
                    "placeholder" => "Synopsis"
                ]
            ])
            ->add('summary',TextareaType::class,[
                "label" => "Sommaire *",
                "attr" => [
                    "placeholder" => "Sommaire"
                ],
                "help" => "* Maximum 500 mots"
            ])
            ->add('poster',UrlType::class,[
                "label" => "Votre image *",
                "attr" => [
                    "placeholder" => "Votre image"
                ],
                "help"=> "* L'url d'une image"
            ])
            ->add('type',ChoiceType::class,[
                "choices" => [
                    "Film" => "Film",
                    "Série" => "Série"
                ],
                "label" => "Film ou série"
            ])
            ->add('genres', EntityType::class,[
                "class" => Genre::class,
                "label" => "Genres *",
                "multiple" => true,
                "expanded" => true,
                "help" => "* Vous pouvez choisir plusieurs genres"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
