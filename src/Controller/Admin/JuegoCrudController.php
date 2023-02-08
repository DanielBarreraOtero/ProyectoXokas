<?php

namespace App\Controller\Admin;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JuegoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Juego::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnDetail(),
            IdField::new('id')
            ->onlyOnIndex(),
            TextField::new('nombre'),
            NumberField::new('anchoTablero'),
            NumberField::new('altoTablero'),
            NumberField::new('minJugadores'),
            NumberField::new('maxJugadores'),
            ImageField::new('imagen')
            ->setBasePath('/img/juegos')
            ->setUploadDir('public\\img\\juegos\\')
            ->onlyOnForms(),
            ImageField::new('imagen')
            ->setBasePath('/img/juegos')
            ->setUploadDir('public\\img\\juegos\\')
            ->onlyOnDetail(),
        ];
    }
}
