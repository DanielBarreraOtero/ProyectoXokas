<?php

namespace App\Form;

use App\Entity\Juego;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditaJuegoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca el nombre',
                    ]),
                ],
            ])
            ->add('descripcion', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca la descripción',
                    ]),
                ],
            ])
            ->add('minJugadores', NumberType::class, [
                'invalid_message' => 'Por favor, introduzca un número',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca un mínimo de jugadores',
                    ]),
                ],
            ])
            ->add('maxJugadores', NumberType::class, [
                'invalid_message' => 'Por favor, introduzca un número',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca un máximo de jugadores',
                    ]),
                ],
            ])
            ->add('anchoTablero', NumberType::class, [
                'invalid_message' => 'Por favor, introduzca un número',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca un ancho (cm)',
                    ]),
                ],
            ])
            ->add('altoTablero', NumberType::class, [
                'invalid_message' => 'Por favor, introduzca un número',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca un alto (cm)',
                    ]),
                ],
            ])
            ->add('imagen', FileType::class, ['data_class' => null, 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
