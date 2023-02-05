<?php

namespace App\Controller\Admin;

use App\Entity\Tramo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TramoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tramo::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
