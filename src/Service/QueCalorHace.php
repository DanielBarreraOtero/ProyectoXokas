<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QueCalorHace
{

    private HttpClientInterface $cliente;
    private string $codMunicipio;
    private string $codProvincia;

    public function __construct(HttpClientInterface $cliente, string $codProvincia, string $codMunicipio)
    {
        $this->cliente = $cliente;
        $this->codMunicipio = $codMunicipio;
        $this->codProvincia = $codProvincia;
    }

    public function setMunicipio(string $codProvincia, string $codMunicipio)
    {
        $this->codMunicipio = $codMunicipio;
        $this->codProvincia = $codProvincia;
    }

    public function getTemperatura()
    {
        $codProv = $this->codProvincia;
        $codMun = $this->codMunicipio;

        $respuesta = $this->cliente->request(
            'GET',
            "https://www.el-tiempo.net/api/json/v2/provincias/$codProv/municipios/$codMun"
        )->toArray();



        return $respuesta['temperatura_actual'];
    }
}
