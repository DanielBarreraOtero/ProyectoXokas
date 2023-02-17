<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $data = [];

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $data['error'] = true;
            $data['mensajeError'] = 'El correo o la contraseña no son correctos';
        } else {
            $data['error'] = false;
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $data['last_username'] = $lastUsername;

        return $this->render('login/index.html.twig', $data);
    }
}
