<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArtisteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artiste::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('nom'),
            TextField::new('pays'),
            TextField::new('photo'),
            
        ];
    }
    
}
