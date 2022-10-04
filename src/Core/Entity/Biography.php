<?php

namespace App\Core\Entity;

class Biography extends Entity {
    private $dateFrom;
    private $dateTo;
    private $whereIs;
    private $biographyDescription;
    private $graveMarker;
    private $spouseInformation;

    public function getDateFrom(){
        return $this->dateFrom;
    }

    public function setDateFrom($dateFrom): void{
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(){
        return $this->dateTo;
    }

    public function setDateTo($dateTo): void{
        $this->dateTo = $dateTo;
    }

    public function getWhereIs(){
        return $this->whereIs;
    }

    public function setWhereIs($whereIs): void{
        $this->whereIs = $whereIs;
    }

    public function getBiographyDescription(){
        return $this->biographyDescription;
    }

    public function setBiographyDescription($biographyDescription): void{
        $this->biographyDescription = $biographyDescription;
    }

    public function getGraveMarker(){
        return $this->graveMarker;
    }

    public function setGraveMarker($graveMarker): void{
        $this->graveMarker = $graveMarker;
    }

    public function getSpouseInformation(){
        return $this->spouseInformation;
    }

    public function setSpouseInformation($spouseInformation): void{
        $this->spouseInformation = $spouseInformation;
    }
}