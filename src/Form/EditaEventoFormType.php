<?php

namespace App\Form;

use App\Entity\Evento;
use App\Entity\Tramo;
use App\Repository\TramoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditaEventoFormType extends AbstractType
{
    private TramoRepository $repoTramo;
    private array $tramos;

    public function __construct(TramoRepository $repoTramo)
    {
        $this->repoTramo = $repoTramo;

        $this->tramos = $this->repoTramo->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('fecha', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime',
                'format' => 'ddMMyyyy',
            ])
            ->add('tramoInicio', EntityType::class, [
                'class' => Tramo::class,
                'choices' => $this->tramos,
                'choice_label' => function (Tramo $tramo) {
                    return $tramo ? $tramo->getHoraInicio()->format('H:i') : '';
                },
            ])
            ->add('tramoFin', EntityType::class, [
                'class' => Tramo::class,
                'choices' => $this->tramos,
                'choice_label' => function (Tramo $tramo) {
                    return $tramo ? $tramo->getHoraFin()->format('H:i') : '';
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evento::class,
        ]);
    }
}
