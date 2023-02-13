<?php

namespace App\Controller;

use App\Entity\Distribucion;
use App\Entity\Posicionamiento;
use App\Repository\DistribucionRepository;
use App\Repository\MesaRepository;
use App\Repository\PosicionamientoRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_posicionamiento")]
class ApiPosicionamientoController extends AbstractController
{
    #[Route('/posicionamiento/{id}', name: 'getPosicionamiento', methods: 'GET')]
    public function getPosicionamiento(PosicionamientoRepository $repoPosi, int $id = null): Response
    {
        if ($id !== null) {
            $posicionamientos[] = $repoPosi->find($id);

            if ($posicionamientos[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado el posicionamiento ' . $id], 404);
            }
        } else {
            $posicionamientos = $repoPosi->findAll();

            if (!isset($posicionamientos[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado posicionamientos'], 404);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($posicionamientos as $posicionamiento) {
            $data['posicionamientos'][] = [
                'id' => $posicionamiento->getId(),
                'mesa_id' => $posicionamiento->getMesa()->getId(),
                'distribucion_id' => $posicionamiento->getDistribucion()->getId(),
                'posX' => $posicionamiento->getPosX(),
                'posY' => $posicionamiento->getPosY(),
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/posicionamiento', name: 'postPosicionamiento', methods: 'POST')]
    public function postPosicionamiento(Request $request, PosicionamientoRepository $repoPosi, MesaRepository $repoMesa, DistribucionRepository $repoDistri): Response
    {
        $posicionamiento = json_decode($request->request->get('posicionamiento'));

        $newPosicionamiento = new Posicionamiento();
        $newPosicionamiento->setMesa($repoMesa->find($posicionamiento->mesa_id));
        $newPosicionamiento->setDistribucion($repoDistri->find($posicionamiento->distribucion_id));
        $newPosicionamiento->setPosX($posicionamiento->posX);
        $newPosicionamiento->setPosY($posicionamiento->posY);

        $repoPosi->save($newPosicionamiento, true);

        return $this->json(['ok' => true, 'posicionamientoi' => $newPosicionamiento], 201);
    }

    #[Route('/posicionamiento', name: 'putPosicionamiento', methods: 'PUT')]
    public function putPosicionamiento(Request $request, DistribucionRepository $repoDistri, PosicionamientoRepository $repoPosi, MesaRepository $repoMesa): Response
    {
        $posicionamiento = json_decode($request->getContent())->posicionamiento;

        $newPosicionamiento = $repoPosi->find($posicionamiento->id);
        $newPosicionamiento->setMesa($repoMesa->find($posicionamiento->mesa_id));
        $newPosicionamiento->setDistribucion($repoDistri->find($posicionamiento->distribucion_id));
        $newPosicionamiento->setPosX($posicionamiento->posX);
        $newPosicionamiento->setPosY($posicionamiento->posY);

        $repoPosi->save($newPosicionamiento);

        return $this->json(['ok' => true, 'posicionamiento' => $newPosicionamiento], 200);
    }

    #[Route('/posicionamiento', name: 'deletePosicionamiento', methods: 'DELETE')]
    public function deletePosicionamiento(Request $request, PosicionamientoRepository $repoPosi): Response
    {
        $posicionamiento = json_decode($request->getContent())->posicionamiento;

        $newPosicionamiento = $repoPosi->find($posicionamiento->id);

        $repoPosi->remove($newPosicionamiento, true);

        return $this->json(['ok' => true, 'posicionamiento' => $newPosicionamiento], 200);
    }
}
