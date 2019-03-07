<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\FrenchToDatetimeTrans;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transform;
    public function __construct(FrenchToDatetimeTrans $transform){
        $this->transform = $transform;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',TextType::class,$this->getConfig("Date arrivée",'La date à la quelle vous pourrez arriver'))
            ->add('endDate',TextType::class,$this->getConfig('Date de départ','La date à la quelle vous quitez les lieux'))
            ->add('comment',TextareaType::class,$this->getConfig(false,'Vous pouvez laisser un commentaire !',['required'=>false]));
        
        $builder->get('startDate')->addModelTransformer($this->transform);
        $builder->get('endDate')->addModelTransformer($this->transform);

        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
