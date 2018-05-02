<?php

namespace App\Form;

use App\Entity\GigImages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GigImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',VichImageType::class)
            ->add('thumbFile',VichImageType::class)
            ->add('gig',null,array('disabled'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GigImages::class,
        ]);
    }
}
