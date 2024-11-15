<?php

namespace App\Moka;

class BinCheck
{

    protected MokaService $mokaService;

    public function __construct(MokaService $mokaService)
    {
        $this->mokaService = $mokaService;
    }


    public function checkBin()
    {
        $authData = $this->mokaService->getAuthData();

    }

}