<?php

namespace App\Controller;

use App\Entity\Distribucion;
use App\Repository\DistribucionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_distribucion")]
class ApiDistribucionController extends AbstractController
{
    #[Route('/distribucion/{id}', name: 'getDistribucion', methods: 'GET')]
    public function getDistribucion(DistribucionRepository $repoDistri, int $id = null): Response
    {
        if ($id !== null) {
            $distribuciones[] = $repoDistri->find($id);

            if ($distribuciones[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado la distribucion ' . $id], 404);
            }
        } else {
            $distribuciones = $repoDistri->findAll();

            if (!isset($distribuciones[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado distribuciones'], 404);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($distribuciones as $distribucion) {
            $data['distribuciones'][] = [
                'id' => $distribucion->getId(),
                'fecha' => $distribucion->getFecha(),
                'mesas' => $distribucion->getMesas()
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/distribucion', name: 'postDistribucion', methods: 'POST')]
    public function postDistribucion(Request $request, DistribucionRepository $repoDistri): Response
    {
        $distribucion = json_decode($request->request->get('distribucion'));

        // dd($distribucion);

        $newDistribucion = new Distribucion();
        $newDistribucion->setFecha(new DateTime($distribucion->fecha->date));
        $newDistribucion->setMesas($distribucion->mesas);

        $repoDistri->save($newDistribucion, true);

        return $this->json(['ok' => true, 'distribucion' => $newDistribucion], 201);
    }

    #[Route('/distribucion', name: 'putDistribucion', methods: 'PUT')]
    public function putDistribucion(Request $request, DistribucionRepository $repoDistri): Response
    {
        $distribucion = json_decode($request->getContent())->distribucion;

        $newDistribucion = $repoDistri->find($distribucion->id);
        $newDistribucion->setFecha($distribucion->fecha);
        $newDistribucion->setMesas($distribucion->mesas);

        $repoDistri->save($newDistribucion);

        return $this->json(['ok' => true, 'distribucion' => $newDistribucion], 200);
    }

    #[Route('/distribucion', name: 'deleteDistribucion', methods: 'DELETE')]
    public function deleteMesa(Request $request, DistribucionRepository $repoDistri): Response
    {
        $distribucion = json_decode($request->getContent())->distribucion;

        $newDistribucion = $repoDistri->find($distribucion->id);

        $repoDistri->remove($newDistribucion, true);

        return $this->json(['ok' => true, 'distribucion' => $newDistribucion], 200);
    }
}
