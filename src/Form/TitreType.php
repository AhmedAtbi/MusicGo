<?php

namespace App\Form;

use App\Entity\Titre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class TitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('duree')
            ->add('url',FileType::class, [
                'label' => 'Fichier audio',
                'mapped' => false,
            ])

            ->add('categorie')
            ->add('artiste')
            ->add('album')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Titre::class,
        ]);
    }
}
