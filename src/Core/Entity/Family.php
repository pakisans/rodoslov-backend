<?php

namespace App\Core\Entity;

class Family extends Entity {
    private $nameOfFamily;

    public function getNameOfFamily(){
        return $this->nameOfFamily;
    }

    public function setNameOfFamily($nameOfFamily): void{
        $this->nameOfFamily = $nameOfFamily;
    }
}