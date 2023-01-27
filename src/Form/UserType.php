<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $builder
            ->add('email',EmailType::class,[
                "label" => "L'email",
                "attr" => [
                    "placeholder" => "L'email"
                ]
            ])
            ->add('roles',ChoiceType::class,[
                "choices"=>[
                    "Manager" => "ROLE_MANAGER",
                    "Admin" => "ROLE_ADMIN"
                ],
                "expanded" => true,
                "multiple" => true
            ]);
            
            // J'utilise les options du formulaire, pour rajouter par défaut un paramètre edit à false,
            // Sa me permert de pouvoir ajouter ce paramètre lors du createForm et dans le cas d'une edition je peux le passer à true, pour cacher le champs password lors d'une edition
        if(!$options["edit"]){
            $builder
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les deux mots de passes doivent être identiques",
                "first_options" => [
                    "label" => "Le mot de passe",
                    "attr" => [
                        "placeholder" => "Le mot de passe"
                    ]
                ],
                "second_options" => [
                    "label" => "Répétez le mot de passe",
                    "attr" => [
                        "placeholder" => "Répétez le mot de passe"
                    ]
                ]
            ]);
        }
    }
    // $builder->add('password', RepeatedType::class, [
//     'type' => PasswordType::class,
//     'invalid_message' => 'The password fields must match.',
//     'options' => ['attr' => ['class' => 'password-field']],
//     'required' => true,
//     'first_options'  => ['label' => 'Password'],
//     'second_options' => ['label' => 'Repeat Password'],
// ]);

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "edit" => false
        ]);
    }
}
