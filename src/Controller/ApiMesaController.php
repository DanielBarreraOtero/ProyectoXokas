<?php

namespace App\Controller;

use App\Entity\Mesa;
use App\Repository\MesaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_mesa")]
class ApiMesaController extends AbstractController
{
    #[Route('/mesa/{id}', name: 'getMesa', methods: 'GET')]
    public function getMesa(MesaRepository $repoMesa, int $id = null): Response
    {
        if ($id !== null) {
            $mesas[] = $repoMesa->find($id);

            if ($mesas[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado la mesa ' . $id], 404);
            }
        } else {
            $mesas = $repoMesa->findAll();

            if (!isset($mesas[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado mesas'], 404);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($mesas as $mesa) {
            $data['mesas'][] = [
                'id' => $mesa->getId(),
                'alto' => $mesa->getAlto(),
                'ancho' => $mesa->getAncho(),
                'posY' => $mesa->getPosY(),
                'posX' => $mesa->getPosX(),
                'sillas' => $mesa->getSillas(),
                'reservas' => $mesa->getReservas()
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/mesa', name: 'postMesa', methods: 'POST')]
    public function postMesa(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $mesa = json_decode($request->request->get('mesa'));

        $newMesa = new Mesa();
        $newMesa->setAlto($mesa->alto);
        $newMesa->setAncho($mesa->ancho);
        $newMesa->setPosY($mesa->posY);
        $newMesa->setPosX($mesa->posX);
        $newMesa->setSillas($mesa->sillas);

        $manager->persist($newMesa);
        $manager->flush();

        return $this->json(['ok' => true, 'mesa' => $newMesa], 201);
    }

    #[Route('/mesa', name: 'putMesa', methods: 'PUT')]
    public function putMesa(Request $request, ManagerRegistry $doctrine, MesaRepository $repoMesa): Response
    {
        $manager = $doctrine->getManager();

        $mesa = json_decode($request->getContent())->mesa;

        $newMesa = $repoMesa->find($mesa->id);
        $newMesa->setAlto($mesa->alto);
        $newMesa->setAncho($mesa->ancho);
        $newMesa->setPosY($mesa->posY);
        $newMesa->setPosX($mesa->posX);
        $newMesa->setSillas($mesa->sillas);

        $manager->persist($newMesa);
        $manager->flush();

        return $this->json(['ok' => true, 'mesa' => $newMesa], 200);
    }

    #[Route('/mesa', name: 'deleteMesa', methods: 'DELETE')]
    public function deleteMesa(Request $request, ManagerRegistry $doctrine, MesaRepository $repoMesa): Response
    {
        $manager = $doctrine->getManager();

        $mesa = json_decode($request->getContent())->mesa;

        $newMesa = $repoMesa->find($mesa->id);

        $manager->remove($newMesa);
        $manager->flush();

        return $this->json(['ok' => true, 'mesa' => $newMesa], 200);
    }
}
