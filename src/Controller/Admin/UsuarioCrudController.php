<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UsuarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            TextField::new('nombre'),
            TextField::new('ap1'),
            TextField::new('ap2'),
            TelephoneField::new('tlf'),
            
        ];
    }
}
