<?php

namespace App\Controller\Admin;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class JuegoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Juego::class;
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
