<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Reserva;
use App\Entity\Usuario;
use App\Repository\MesaRepository;
use App\Repository\ReservaRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_reserva")]
class ApiReservaController extends AbstractController
{
    #[Route('/reserva/{id}', name: 'getReserva', methods: 'GET')]
    public function getReserva(ReservaRepository $repoReser, int $id = null): Response
    {
        if ($id !== null) {
            $reservas[] = $repoReser->find($id);

            if ($reservas[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado la reserva ' . $id], 404);
            }
        } else {
            $reservas = $repoReser->findAll();

            if (!isset($reservas[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado reservas'], 404);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($reservas as $reserva) {
            $data['reservas'][] = [
                'id' => $reserva->getId(),
                'mesa_id' => $reserva->getMesa()->getId(),
                'juego_id' => $reserva->getJuegos()->getId(),
                'usuario_id' => $reserva->getUsuario()->getId(),
                'asiste' => $reserva->isAsiste(),
                'fecha' => $reserva->getFecha(),
                'tramoInicio' => $reserva->getTramoInicio(),
                'tramoFin' => $reserva->getTramoFin(),
                'fecha_cancelacion' => $reserva->getFechaCancelacion()
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/reserva', name: 'postReserva', methods: 'POST')]
    public function postReserva(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $reserva = json_decode($request->request->get('reserva'));

        $newReserva = new Reserva();
        $newReserva->setMesa($manager->getRepository(Mesa::class)->find($reserva->mesa_id));
        $newReserva->setUsuario($manager->getRepository(Usuario::class)->find($reserva->usuario_id));
        $newReserva->setJuegos($manager->getRepository(Juego::class)->find($reserva->juego_id));
        $newReserva->setFecha(new DateTime($reserva->fecha->date));
        $newReserva->setTramoInicio($manager->getRepository(Tramo::class)->find($reserva->tramo_inicio_id));
        $newReserva->setTramoFin($manager->getRepository(Tramo::class)->find($reserva->tramo_fin_id));
        $newReserva->setAsiste(true);
        
        $manager->persist($newReserva);
        $manager->flush();
        
        return $this->json(['ok' => true, 'reserva' => $newReserva], 201);
    }
    
    #[Route('/reserva', name: 'putReserva', methods: 'PUT')]
    public function putReserva(Request $request, ManagerRegistry $doctrine, ReservaRepository $repoReser): Response
    {
        $manager = $doctrine->getManager();
        
        $reserva = json_decode($request->getContent())->reserva;
        
        $newReserva = $repoReser->find($reserva->id);
        
        $newReserva->setMesa($manager->getRepository(Mesa::class)->find($reserva->mesa_id));
        $newReserva->setUsuario($manager->getRepository(Usuario::class)->find($reserva->usuario_id));
        $newReserva->setJuegos($manager->getRepository(Juego::class)->find($reserva->juego_id));
        $newReserva->setTramoInicio($manager->getRepository(Tramo::class)->find($reserva->tramo_inicio_id));
        $newReserva->setTramoFin($manager->getRepository(Tramo::class)->find($reserva->tramo_fin_id));
        $newReserva->setFecha(new DateTime($reserva->fecha->date));
        
        if (isset($reserva->fecha_cancelacion) && $reserva->fecha_cancelacion !== null) {
            $newReserva->setFechaCancelacion(new DateTime($reserva->fecha_cancelacion->date));
        }
        $newReserva->setAsiste($reserva->asiste);

        $manager->persist($newReserva);
        $manager->flush();

        return $this->json(['ok' => true, 'reserva' => $newReserva], 200);
    }

    #[Route('/reserva', name: 'deleteReserva', methods: 'DELETE')]
    public function deleteReserva(Request $request, ManagerRegistry $doctrine, ReservaRepository $repoReser): Response
    {
        $manager = $doctrine->getManager();

        $reserva = json_decode($request->getContent())->reserva;

        $newReserva = $repoReser->find($reserva->id);

        $manager->remove($newReserva);
        $manager->flush();

        return $this->json(['ok' => true, 'reserva' => $newReserva], 200);
    }
}
