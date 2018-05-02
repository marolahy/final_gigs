<?php

namespace App\Form;

use App\Entity\Gigs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GigsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price')
            ->add('name')
            ->add('shortdescription')
            ->add('description', CKEditorType::class)
            ->add('featured')
            ->add('stock')
            ->add('selled')
            ->add('icon',FileType::class)
            ->add('background',FileType::class)
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gigs::class,
        ]);
    }
}
