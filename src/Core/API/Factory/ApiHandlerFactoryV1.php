<?php

namespace App\Core\API\Factory;

use App\Core\API\HandlerType;

class ApiHandlerFactoryV1 {
    public function getHandler($type, $em, $container, $logger, $admin){
        if($type == HandlerType::User){
            return new \App\Core\API\V1\UserHandler($em, $container, $logger);
        }
    }
}