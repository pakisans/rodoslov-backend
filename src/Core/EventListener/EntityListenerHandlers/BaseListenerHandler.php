<?php

namespace App\Core\EventListener\EntityListenerHandlers;


class BaseListenerHandler
{
    protected $entity;

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function prePersist()
    {
        $this->entity->setDateCreated(new \DateTime());
        $this->entity->setDateUpdated(new \DateTime());
        $this->entity->setDeleted(false);
    }

    public function preUpdate()
    {
        $this->entity->setDateUpdated(new \DateTime());
    }
}