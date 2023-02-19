<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\CreaJuegoFormType;
use App\Form\EditaJuegoFormType;
use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use App\Service\Calculadora;
use App\Service\MessageGenerator;
use App\Service\PintaNombre;
use App\Service\QueCalorHace;
use App\Service\XokasMailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JuegoRepository $repJuego): Response
    {
        $juegos = $repJuego->findAll();

        return $this->render('index.html.twig', ['juegos' => $juegos]);
    }

    // TODO: Hacer que solo pueda acceder un admin
    #[Route('/formularioJuego/{id}', name: 'formularioJuego')]
    public function formuJuego(Request $request, JuegoRepository $repoJueg, SluggerInterface $slugger, int $id = null): Response
    {
        if ($id !== null) {
            $juego = $repoJueg->find($id);
            $formuJuego = $this->createForm(EditaJuegoFormType::class, $juego);
            $img = $juego->getImagen();
        } else {
            $juego = new Juego();
            $formuJuego = $this->createForm(CreaJuegoFormType::class, $juego);
        }

        $formuJuego->handleRequest($request);

        if ($formuJuego->isSubmitted() && $formuJuego->isValid()) {

            if ($id === null || $formuJuego['imagen']->getData() !== null) {
                $img = $formuJuego['imagen']->getData();
            }

            $nuevoJuego = $formuJuego->getData();

            if ($id === null || $formuJuego['imagen']->getData() !== null) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFilename);
                $newFilename = $safeFileName . '-' . uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move('img/juegos/', $newFilename);

                    $nuevoJuego->setImagen($newFilename);
                } catch (FileException $e) {
                    throw $e;
                }
            } else {
                $nuevoJuego->setImagen($img);
            }

            $repoJueg->save($nuevoJuego, true);

            return $this->redirectToRoute('home');
        }

        return $this->render('juegos/formulario.html.twig', ['formuJuego' => $formuJuego, 'juego' => $juego]);
    }

    // TODO: Hacer que solo pueda acceder un admin
    #[Route('/borraJuego/{id}', name: 'borraJuego')]
    public function FunctionName(JuegoRepository $repoJueg, int $id): Response
    {
        $juego = $repoJueg->find($id);

        $repoJueg->remove($juego, true);

        return $this->redirectToRoute('home');
    }

    #[Route('/mantenimientoSala', name: 'mantenimientoSala')]
    public function mantenimientoSala(): Response
    {
        return $this->render('sala/mantenimiento.html.twig');
    }

    // TODO: Hacer que solo pueda acceder un admin
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
        die("" . $calc->suma($a, $b));
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

        die("hace " . $calo->getTemperatura() . "º");
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
