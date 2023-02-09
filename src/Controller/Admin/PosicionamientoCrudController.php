<?php

namespace App\Controller\Admin;

use App\Entity\Posicionamiento;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class PosicionamientoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Posicionamiento::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),
            IdField::new('id')
            ->onlyOnDetail(),
            AssociationField::new('distribucion'),
            AssociationField::new('mesa'),
            NumberField::new('posX'),
            NumberField::new('posY'),
        ];
    }
}
