<?php

namespace App\Service;

use App\Repository\UsuarioRepository;

class PintaNombre {

    private UsuarioRepository $ur;


    public function __construct(UsuarioRepository $ur)
    {
        $this->ur = $ur;
    }

    public function getNombre(int $id)
    {   
        $container->hasParameter();
        $usu = $this->ur->find($id);

        return $usu->getNombre();
    }

}