<?php

namespace App\Core\Entity;

class Entity {
    protected $id;
    protected $date_created;
    protected $date_updated;
    protected $deleted;

    public function getId(){
        return $this->id;
    }

    public function setId($id): void{
        $this->id = $id;
    }

    public function getDateCreated(){
        return $this->date_created;
    }

    public function setDateCreated($date_created): void{
        $this->date_created = $date_created;
    }

    public function getDateUpdated(){
        return $this->date_updated;
    }

    public function setDateUpdated($date_updated): void{
        $this->date_updated = $date_updated;
    }

    public function getDeleted(){
        return $this->deleted;
    }

    public function setDeleted($deleted): void{
        $this->deleted = $deleted;
    }
}