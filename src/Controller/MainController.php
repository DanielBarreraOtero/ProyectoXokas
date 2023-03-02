<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Juego;
use App\Form\CreaEventoFormType;
use App\Form\CreaJuegoFormType;
use App\Form\EditaEventoFormType;
use App\Form\EditaJuegoFormType;
use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use App\Repository\TramoRepository;
use App\Repository\UsuarioRepository;
use App\Service\Calculadora;
use App\Service\MessageGenerator;
use App\Service\PintaNombre;
use App\Service\QueCalorHace;
use App\Service\XokasMailer;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    public function index(JuegoRepository $repJuego, TramoRepository $repTramo): Response
    {
        $juegos = $repJuego->findAll();
        $tramos = $repTramo->findAll();

        return $this->render('index.html.twig', ['juegos' => $juegos, 'tramos' => $tramos]);
    }

    #[Route('/eventos', name: 'eventos')]
    public function eventos(EventoRepository $repoEvent): Response
    {
        $eventos = $repoEvent->findAll();


        return $this->render('eventos/index.html.twig', ['eventos' => $eventos]);
    }

    #[IsGranted("ROLE_ADMIN")]
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

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/borraJuego/{id}', name: 'borraJuego')]
    public function borraJuego(JuegoRepository $repoJueg, int $id): Response
    {
        $juego = $repoJueg->find($id);

        $repoJueg->remove($juego, true);

        return $this->redirectToRoute('home');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/formularioEvento/{id}', name: 'formularioEvento')]
    public function formuEvento(Request $request, EventoRepository $repoEven, SluggerInterface $slugger, int $id = null)
    {
        if ($id !== null) {
            $evento = $repoEven->find($id);
            $formuEvento = $this->createForm(EditaEventoFormType::class, $evento);
            // $img = $eve->getImagen();
        } else {
            $evento = new Evento();
            $formuEvento = $this->createForm(EditaEventoFormType::class, $evento);
        }

        $formuEvento->handleRequest($request);

        if ($formuEvento->isSubmitted() && $formuEvento->isValid()) {

            // if ($id === null || $formuEvento['imagen']->getData() !== null) {
            //     $img = $formuEvento['imagen']->getData();
            // }

            $nuevoEvento = $formuEvento->getData();

            // if ($id === null || $formuEvento['imagen']->getData() !== null) {
            //     $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            //     $safeFileName = $slugger->slug($originalFilename);
            //     $newFilename = $safeFileName . '-' . uniqid() . '.' . $img->guessExtension();

            //     try {
            //         $img->move('img/juegos/', $newFilename);

            //         $nuevoEvento->setImagen($newFilename);
            //     } catch (FileException $e) {
            //         throw $e;
            //     }
            // } else {
            //     $nuevoEvento->setImagen($img);
            // }

            $repoEven->save($nuevoEvento, true);

            return $this->redirectToRoute('eventos');
        }

        return $this->render('eventos/formulario.html.twig', [
            'formuEvento' => $formuEvento,
            'evento' => $evento,
            "invitaciones" => $evento->getInvitacionesNotLazy(),
            "presentaciones" => $evento->getPresentacionesNotLazy()
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/borraEvento/{id}', name: 'borraEvento')]
    public function borraEvento(EventoRepository $repoEven, int $id)
    {
        $evento = $repoEven->find($id);

        $repoEven->remove($evento, true);

        return $this->redirectToRoute('eventos');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/mantenimientoSala', name: 'mantenimientoSala')]
    public function mantenimientoSala(): Response
    {
        return $this->render('sala/mantenimiento.html.twig');
    }

    #[IsGranted("ROLE_ADMIN")]
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
