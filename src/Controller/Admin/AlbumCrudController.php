<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            
            TextField::new('nom'),
            TextEditorField::new('description'),
            TextField::new('image'),
            AssociationField::new('categorie')
            ->autocomplete(),
            AssociationField::new('artiste')
                ->autocomplete(),
        ];
    }
    
}
