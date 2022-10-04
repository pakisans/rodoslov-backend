<?php

namespace App\Core\EventListener\EntityListenerHandlers;

class EntityHandlerFactory
{
    public static function getHandler($entity, $container)
    {
        $listener = null;

        $listener = new BaseListenerHandler();
        $listener->setEntity($entity);

        return $listener;
    }
}