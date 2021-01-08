<?php

namespace App\Controller\Admin;

use App\Entity\Titre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TitreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Titre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('duree'),
            TextField::new('url'),
            AssociationField::new('categorie')
            ->autocomplete(),
            AssociationField::new('artiste')
                ->autocomplete(),
            AssociationField::new('album')
                ->autocomplete(),

        ];
    }
    
}
