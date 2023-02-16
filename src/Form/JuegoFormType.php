<?php

namespace App\Form;

use App\Entity\Juego;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JuegoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('minJugadores')
            ->add('maxJugadores')
            ->add('anchoTablero')
            ->add('altoTablero')
            ->add('imagen', FileType::class, ['data_class' => null])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
