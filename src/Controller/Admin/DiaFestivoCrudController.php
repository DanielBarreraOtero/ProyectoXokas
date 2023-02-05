<?php

namespace App\Controller\Admin;

use App\Entity\DiaFestivo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DiaFestivoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DiaFestivo::class;
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
