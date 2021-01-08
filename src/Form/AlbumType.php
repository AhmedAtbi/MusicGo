<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('photo')
            ->add('categorie')
            ->add('artiste')
        ;
    }

    public function search($mots = null, $categorie = null){
        $query = $this->createQueryBuilder('a');
        $query->where('a.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.title, a.content) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('a.categories', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie);
        }
        return $query->getQuery()->getResult();
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
