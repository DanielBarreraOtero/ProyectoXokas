<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_juego")]
class ApiJuegoController extends AbstractController
{
    #[Route('/juego/{id}', name: 'getJuego', methods: 'GET')]
    public function getJuego(JuegoRepository $repoJuego, int $id = null): Response
    {
        if ($id !== null) {
            $juegos[] = $repoJuego->find($id);

            if ($juegos[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado el juego ' . $id], 404);
            }
        } else {
            $juegos = $repoJuego->findAll();

            if (!isset($juegos[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado juegos'], 404);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($juegos as $juego) {
            $data['juegos'][] = [
                'id' => $juego->getId(),
                'nombre' => $juego->getNombre(),
                'anchoTablero' => $juego->getAnchoTablero(),
                'altoTablero' => $juego->getAltoTablero(),
                'minJugadores' => $juego->getMinJugadores(),
                'maxJugadores' => $juego->getMaxJugadores(),
                'presentaciones' => $juego->getPresentaciones(),
                'reservas' => $juego->getReservas()
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/juego', name: 'postJuego', methods: 'POST')]
    public function postJuego(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $juego = json_decode($request->request->get('juego'));

        $newJuego = new Juego();
        $newJuego->setNombre($juego->nombre);
        $newJuego->setAltoTablero($juego->altoTablero);
        $newJuego->setAnchoTablero($juego->anchoTablero);
        $newJuego->setMinJugadores($juego->minJugadores);
        $newJuego->setMaxJugadores($juego->maxJugadores);

        $manager->persist($newJuego);
        $manager->flush();

        return $this->json(['ok' => true, 'juego' => $newJuego], 201);
    }

    #[Route('/juego', name: 'putJuego', methods: 'PUT')]
    public function putJuego(Request $request, ManagerRegistry $doctrine, JuegoRepository $repoJuego): Response
    {
        $manager = $doctrine->getManager();

        $juego = json_decode($request->getContent())->juego;

        $newJuego = $repoJuego->find($juego->id);
        $newJuego->setNombre($juego->nombre);
        $newJuego->setAltoTablero($juego->altoTablero);
        $newJuego->setAnchoTablero($juego->anchoTablero);
        $newJuego->setMinJugadores($juego->minJugadores);
        $newJuego->setMaxJugadores($juego->maxJugadores);

        $manager->persist($newJuego);
        $manager->flush();

        return $this->json(['ok' => true, 'juego' => $newJuego], 200);
    }

    #[Route('/juego', name: 'deleteJuego', methods: 'DELETE')]
    public function deleteJuego(Request $request, ManagerRegistry $doctrine, JuegoRepository $repoJuego): Response
    {
        $manager = $doctrine->getManager();

        $juego = json_decode($request->getContent())->juego;

        $newJuego = $repoJuego->find($juego->id);

        $manager->remove($newJuego);
        $manager->flush();

        return $this->json(['ok' => true, 'mesa' => $newJuego], 200);
    }
}
