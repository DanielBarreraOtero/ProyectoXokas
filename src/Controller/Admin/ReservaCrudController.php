<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ReservaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reserva::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),
            BooleanField::new('asiste'),
            DateField::new('fecha'),
            AssociationField::new('tramos')
            ->onlyOnForms(),
            ArrayField::new('tramos')
            ->onlyOnDetail(),
            ArrayField::new('tramos')
            ->onlyOnIndex(),
            AssociationField::new('mesa'),
            AssociationField::new('juegos'),
            AssociationField::new('usuario'),
            DateField::new('fechaCancelacion')
            ->onlyWhenUpdating(),
            DateField::new('fechaCancelacion')
            ->onlyOnDetail(),
            DateField::new('fechaCancelacion')
            ->onlyOnIndex()
        ];
    }
}
