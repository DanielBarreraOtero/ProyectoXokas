<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_usuario")]
class ApiUsuarioController extends AbstractController
{
    #[Route('/usuario/{id}', name: 'getUsuario', methods: 'GET')]
    public function getUsuario(UsuarioRepository $repoUsua, int $id = null): Response
    {
        if ($id !== null) {
            $usuarios[] = $repoUsua->find($id);

            if ($usuarios[0] === null) {
                return $this->json(['ok' => false, 'message' => 'no se ha encontrado el usuario ' . $id], 200);
            }
        } else {
            $usuarios = $repoUsua->findAll();

            if (!isset($usuarios[0])) {
                return $this->json(['ok' => false, 'message' => 'no se han encontrado usuarios'], 200);
            }
        }

        $data = [];
        $data['ok'] = true;

        foreach ($usuarios as $usuario) {
            $data['usuarios'][] = $usuario;
        }

        return $this->json($data, 200);
    }

    #[Route('/usuario/evento/{id}', name: 'getUsuarioByEvento', methods: 'GET')]
    public function getUsuarioByEvento(UsuarioRepository $repoUsua, int $id): Response
    {
        $usuarios = $repoUsua->findByEventoId($id);

        if (!isset($usuarios[0])) {
            return $this->json(['ok' => false, 'message' => 'no se han encontrado usuarios'], 200);
        }

        $data = [];
        $data['ok'] = true;

        foreach ($usuarios as $usuario) {
            $data['usuarios'][] = $usuario;
        }

        return $this->json($data, 200);
    }

    /**
     * Usuario invitables a un evento (usuarios administradores no pueden participar en eventos)
     *      
     */
    #[Route('/usuario/notEvento/{id}', name: 'getUsuarioByNotEvento', methods: 'GET')]
    public function getUsuarioByNotEvento(UsuarioRepository $repoUsua, int $id): Response
    {
        $usuarios = $repoUsua->findByNotInEventoId($id);

        if (!isset($usuarios[0])) {
            return $this->json(['ok' => false, 'message' => 'no se han encontrado usuarios'], 200);
        }

        $data = [];
        $data['ok'] = true;

        foreach ($usuarios as $usuario) {
            $data['usuarios'][] = $usuario;
        }

        return $this->json($data, 200);
    }
}
