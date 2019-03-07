<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfig('Titre','Taper un super titre de votre annonce'))
            ->add(
                'slug',
                TextType::class,
                $this->getConfig('Adresse web','Taper votre adresse web',['required'=>false]))
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfig('Image principale','votre Image principale de la annonce'))
            ->add(
                'introduction',
                TextType::class,
                $this->getConfig('Introduction','Donner une introduction globale de votre annonce'))
            ->add(
                'content',
                TextareaType::class,
                $this->getConfig('Description','Taper une description qui donne vraiment envie de venir chez vous!'))
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfig('Nombre de chambres','Nombre de chambres disponible'))
            ->add(
                'price',
                MoneyType::class,
                $this->getConfig('Prix','votre prix'))
            ->add(
                'images',
                CollectionType::class,
                [
                 'entry_type' => ImageType::class,
                 'allow_add' => true,
                 'allow_delete' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
