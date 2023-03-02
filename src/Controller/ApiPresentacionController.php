<?php

namespace App\Controller;

use App\Entity\Presentacion;
use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use App\Repository\PresentacionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_presentacion")]
class ApiPresentacionController extends AbstractController
{
    // #[Route('/invitacion/{id}', name: 'getInvitacion', methods: 'GET')]
    // public function getInvitacion(InvitacionRepository $repoInvi, int $id = null): Response
    // {
    //     if ($id !== null) {
    //         $mesas[] = $repoMesa->find($id);

    //         if ($mesas[0] === null) {
    //             return $this->json(['ok' => false, 'message' => 'no se ha encontrado la mesa ' . $id], 404);
    //         }
    //     } else {
    //         $mesas = $repoMesa->findAll();

    //         if (!isset($mesas[0])) {
    //             return $this->json(['ok' => false, 'message' => 'no se han encontrado mesas'], 404);
    //         }
    //     }

    //     $data = [];
    //     $data['ok'] = true;

    //     foreach ($mesas as $mesa) {
    //         $data['mesas'][] = $mesa;
    //     }

    //     return $this->json($data, 200);
    // }

    #[Route('/presentacion', name: 'postPresentacion', methods: 'POST')]
    public function postPresentacion(Request $request, PresentacionRepository $repoPresentacion, EventoRepository $repoEvent, JuegoRepository $repoJueg): Response
    {
        $presentacion = json_decode($request->request->get('presentacion'));

        $newPresentacion = new Presentacion();
        $newPresentacion->setEvento($repoEvent->find($presentacion->evento_id));
        $newPresentacion->setJuego($repoJueg->find($presentacion->juego_id));

        $repoPresentacion->save($newPresentacion, true);

        return $this->json(['ok' => true, 'presentacion' => $newPresentacion], 201);
    }

    // #[Route('/mesa', name: 'putMesa', methods: 'PUT')]
    // public function putMesa(Request $request, ManagerRegistry $doctrine, MesaRepository $repoMesa): Response
    // {
    //     $manager = $doctrine->getManager();

    //     $mesa = json_decode($request->getContent())->mesa;

    //     $newMesa = $repoMesa->find($mesa->id);
    //     $newMesa->setAlto($mesa->alto);
    //     $newMesa->setAncho($mesa->ancho);
    //     $newMesa->setPosY($mesa->posY);
    //     $newMesa->setPosX($mesa->posX);
    //     $newMesa->setSillas($mesa->sillas);

    //     $manager->persist($newMesa);
    //     $manager->flush();

    //     return $this->json(['ok' => true, 'mesa' => $newMesa], 200);
    // }

    #[Route('/presentacion', name: 'deletePresentacion', methods: 'DELETE')]
    public function deletePresentacion(Request $request, ManagerRegistry $doctrine, PresentacionRepository $repoPresentacion): Response
    {
        $manager = $doctrine->getManager();

        $presentacion = json_decode($request->getContent())->presentacion;

        $newPresentacion = $repoPresentacion->find($presentacion->id);

        $manager->remove($newPresentacion);
        $manager->flush();

        return $this->json(['ok' => true, 'presentacion' => $newPresentacion], 200);
    }
}
