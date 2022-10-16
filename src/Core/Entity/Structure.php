<?php

namespace App\Core\Entity;

class Structure extends Entity {
    private $version;
    private $fromDate;
    private $toDate;

    private $family;
    private $subordinate;
    private $superior;

    public function getVersion(){
        return $this->version;
    }

    public function setVersion($version): void{
        $this->version = $version;
    }

    public function getFromDate(){
        return $this->fromDate;
    }

    public function setFromDate($fromDate): void{
        $this->fromDate = $fromDate;
    }

    public function getToDate(){
        return $this->toDate;
    }

    public function setToDate($toDate): void{
        $this->toDate = $toDate;
    }

    public function getFamily(){
        return $this->family;
    }

    public function setFamily($family): void{
        $this->family = $family;
    }

    public function getSubordinate(){
        return $this->subordinate;
    }

    public function setSubordinate($subordinate): void{
        $this->subordinate = $subordinate;
    }

    public function getSuperior(){
        return $this->superior;
    }

    public function setSuperior($superior): void{
        $this->superior = $superior;
    }
}
