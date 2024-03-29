<?php

namespace App\Core\Entity;

class Sheets extends Entity {
    private $firstName;
    private $currentLevel;
    private $isStructure;
    private $dateOfBirth;
    private $dateOfDeath;
    private $address;
    private $photo;
    private $family;

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName($firstName): void{
        $this->firstName = $firstName;
    }

    public function getCurrentLevel(){
        return $this->currentLevel;
    }

    public function setCurrentLevel($currentLevel): void{
        $this->currentLevel = $currentLevel;
    }

    public function getIsStructure(){
        return $this->isStructure;
    }

    public function setIsStructure($isStructure): void{
        $this->isStructure = $isStructure;
    }

    public function getDateOfBirth(){
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth): void{
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getDateOfDeath(){
        return $this->dateOfDeath;
    }

    public function setDateOfDeath($dateOfDeath): void{
        $this->dateOfDeath = $dateOfDeath;
    }

    public function getAddress(){
        return $this->address;
    }

    public function setAddress($address): void{
        $this->address = $address;
    }

    public function getPhoto(){
        return $this->photo;
    }

    public function setPhoto($photo): void{
        $this->photo = $photo;
    }

    public function getFamily(){
        return $this->family;
    }

    public function setFamily($family): void{
        $this->family = $family;
    }

    public function renderDateOfDeath($date){
        if($date){
            return date_format($date, 'Y');
        }
        return '';
    }

    public function getFullName(){
        return $this->getFirstName() .' '.
            date_format($this->getDateOfBirth(), 'Y') .' - '.
            $this->renderDateOfDeath($this->getDateOfDeath());
    }
}