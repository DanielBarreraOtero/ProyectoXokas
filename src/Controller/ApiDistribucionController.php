<?php

namespace App\Controller;

use App\Entity\Distribucion;
use App\Entity\Posicionamiento;
use App\Repository\DistribucionRepository;
use App\Repository\PosicionamientoRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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
                'posicionamientos' => $distribucion->getPosicionamientos()->toArray()
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('/distribucion', name: 'postDistribucion', methods: 'POST')]
    public function postDistribucion(Request $request, DistribucionRepository $repoDistri): Response
    {
        $distribucion = json_decode($request->request->get('distribucion'));

        $newDistribucion = new Distribucion();
        $newDistribucion->setFecha(new DateTime($distribucion->fecha));

        $repoDistri->save($newDistribucion, true);

        return $this->json(['ok' => true, 'distribucion' => $newDistribucion], 201);
    }

    #[Route('/distribucion', name: 'deleteDistribucion', methods: 'DELETE')]
    public function deleteDistribucion(Request $request, DistribucionRepository $repoDistri): Response
    {
        $distribucion = json_decode($request->getContent())->distribucion;

        $newDistribucion = $repoDistri->find($distribucion->id);

        $repoDistri->remove($newDistribucion, true);

        return $this->json(['ok' => true, 'distribucion' => $newDistribucion], 200);
    }
}
