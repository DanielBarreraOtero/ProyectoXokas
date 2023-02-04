<?php

namespace App\Service;

class Calculadora {

    private Sumador $sumador;

    public function __construct(Sumador $sumador)
    {
        $this->sumador = $sumador;   
    }

    public function suma(int $a, int $b)
    {
        return $this->sumador->suma($a, $b);
    }

}