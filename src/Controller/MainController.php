<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('logeado/index.html.twig');
    }

    #[Route('/haceAdmin/{id}', name: 'haceAdmin')]
    public function haceAdmin(UsuarioRepository $repo, ManagerRegistry $manager, int $id): Response
    {
        $usuario = $repo->find($id);

        $usuario->setRoles(['ROLE_ADMIN']);

        $manager->getManager()->persist($usuario);
        $manager->getManager()->flush();

        dd($usuario);
    }

    #[Route('/soloAdmins', name: 'soloAdmins')]
    public function soloAdmins(): Response
    {
        return $this->render('logeado/admins.html.twig');
    }
}
