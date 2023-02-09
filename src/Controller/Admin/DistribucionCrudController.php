<?php

namespace App\Controller\Admin;

use App\Entity\Distribucion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DistribucionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Distribucion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),
            IdField::new('id')
            ->onlyOnDetail(),
            DateField::new('fecha'),
            ArrayField::new('mesas')
        ];
    }
}
