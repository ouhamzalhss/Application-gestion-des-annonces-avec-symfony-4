<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,$this->getConfig("Ancien mot de passe","Veuillez entrer votre mot de passe actuel"))
            ->add('newPassword',PasswordType::class,$this->getConfig("le mot de passe","Veuillez entrer le nouveau mot de passe"))
            ->add('confimPassword',PasswordType::class,$this->getConfig("Confirmation de mot de passe","Confirmation"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
