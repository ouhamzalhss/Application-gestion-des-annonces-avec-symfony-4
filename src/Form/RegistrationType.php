<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfig("Prénom","votre prenom"))
            ->add('lastName',TextType::class,$this->getConfig("Nom","votre nom de famille"))
            ->add('email',EmailType::class,$this->getConfig("Email","votre adresse email"))
            ->add('picture',UrlType::class,$this->getConfig("photo de profile","URL de votre photo avatar..."))
            ->add('hash',PasswordType::class,$this->getConfig("Mot  de passe","Un bon mot de passe"))
            ->add('passwordConfirm',PasswordType::class,$this->getConfig("Confirmatiob de Mot  de passe","Veuillez confirmer le mot de passe"))
            ->add('introduction',TextType::class,$this->getConfig("Introduction","Presentez-vous ..."))
            ->add('description',TextareaType::class,$this->getConfig("Description detaille","C'est le moment de vous presenter en detailles"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

  
}
