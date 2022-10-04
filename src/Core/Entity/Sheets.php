<?php

namespace App\Core\Entity;

class Sheets extends Entity {
    private $fullName;
    private $currentLevel;
    private $isStructure; // ?????
    private $isValid; // ?????
    private $dateOfBirth;
    private $dateOfDeath;
    private $address;
    private $photo;

    public function getFullName(){
        return $this->fullName;
    }

    public function setFullName($fullName): void{
        $this->fullName = $fullName;
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

    public function getIsValid(){
        return $this->isValid;
    }

    public function setIsValid($isValid): void{
        $this->isValid = $isValid;
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
}