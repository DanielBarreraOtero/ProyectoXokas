<?php

namespace App\Controller;

use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use App\Service\Calculadora;
use App\Service\MessageGenerator;
use App\Service\PintaNombre;
use App\Service\QueCalorHace;
use App\Service\XokasMailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JuegoRepository $repJuego): Response
    {
        $juegos = $repJuego->findAll();

        return $this->render('index.html.twig', ['juegos' => $juegos]);
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

// #region test servicios
    
    #[Route('/serviceTest', name: 'serviceTest')]
    public function serviceTest(MessageGenerator $mg): Response
    {
        die($mg->getMessage());
    }

    #[Route('/suma/{a}/{b}', name: 'suma')]
    public function suma(Calculadora $calc, int $a, int $b): Response
    {
        die("".$calc->suma($a , $b));
    }

    #[Route('/pintaNombre/{id}', name: 'pintaNombre')]
    public function pintaNombre(PintaNombre $pN, int $id): Response
    {
        die($pN->getNombre($id));
    }

    #[Route('/queCalo', name: 'queCalo')]
    public function queCalo(QueCalorHace $calo): Response
    {
        // cambiamos el municipio, comentar para que muestre jaen
        // $calo->setMunicipio("23", "23010");

        die("hace ".$calo->getTemperatura()."º");
    }

    #[Route('/email')]
    public function sendEmail(XokasMailer $mailer): Response
    {
        // $for = 'dbarote0812@g.educaand.es';
        $for = 'vesqgar3008@g.educaand.es';
        $mailer->setMailSpam($for);

        $mailer->sendMail();

        die('enviado');
    }

    // #endregion
}
