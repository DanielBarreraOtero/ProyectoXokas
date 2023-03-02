<?php

namespace App\Controller;

use App\Entity\Invitacion;
use App\Repository\EventoRepository;
use App\Repository\InvitacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_invitacion")]
class ApiInvitacionController extends AbstractController
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

    #[Route('/invitacion', name: 'postInvitacion', methods: 'POST')]
    public function postInvitacion(Request $request, InvitacionRepository $repoInvi, EventoRepository $repoEvent, UsuarioRepository $repoUsu): Response
    {
        $invitacion = json_decode($request->request->get('invitacion'));

        $newInvitacion = new Invitacion();
        $newInvitacion->setEvento($repoEvent->find($invitacion->evento_id));
        $newInvitacion->setUsuario($repoUsu->find($invitacion->usuario_id));
        $newInvitacion->setAsiste($invitacion->asiste);

        $repoInvi->save($newInvitacion, true);

        return $this->json(['ok' => true, 'invitacion' => $newInvitacion], 201);
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

    #[Route('/invitacion', name: 'deleteInvitacion', methods: 'DELETE')]
    public function deleteInvitacion(Request $request, ManagerRegistry $doctrine, InvitacionRepository $repoInvitacion): Response
    {
        $manager = $doctrine->getManager();

        $invitacion = json_decode($request->getContent())->invitacion;

        $newInvitacion = $repoInvitacion->find($invitacion->id);

        $manager->remove($newInvitacion);
        $manager->flush();

        return $this->json(['ok' => true, 'invitacion' => $newInvitacion], 200);
    }
}
