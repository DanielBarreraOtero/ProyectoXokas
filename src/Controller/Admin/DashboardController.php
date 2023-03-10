<?php

namespace App\Controller\Admin;

use App\Entity\DiaFestivo;
use App\Entity\Distribucion;
use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Posicionamiento;
use App\Entity\Reserva;
use App\Entity\Tramo;
use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        return $this->render('admin/index.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProyectoXokas');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Inicio', 'fa fa-home', $this->generateUrl('home'));
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', Usuario::class);
        yield MenuItem::linkToCrud('Juegos', 'fa fa-chess', Juego::class);
        yield MenuItem::linkToCrud('Mesas', 'fa fa-square', Mesa::class);
        yield MenuItem::linkToCrud('Reservas', 'fa fa-calendar-day', Reserva::class);
        yield MenuItem::linkToCrud('Tramos', 'fa fa-clock', Tramo::class);
        yield MenuItem::linkToCrud('DiasFestivos', 'fa fa-calendar', DiaFestivo::class);
        yield MenuItem::linkToCrud('Distribuciones', 'fa fa-inbox', Distribucion::class);
        yield MenuItem::linkToCrud('Posicionamientos', 'fa fa-location-dot', Posicionamiento::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
