<?php

namespace App\Core\API\Factory;

use App\Core\API\HandlerType;

class ApiHandlerFactoryV1 {
    public function getHandler($type, $em, $container, $logger, $admin){
        if($type == HandlerType::User){
            return new \App\Core\API\V1\UserHandler($em, $container, $logger);
        }else if($type == HandlerType::Family){
            return $admin ? null : new \App\Core\API\V1\FamilyHandler($em, $container, $logger);
        }else if($type == HandlerType::Sheets){
            return $admin ? null : new \App\Core\API\V1\SheetsHandler($em, $container, $logger);
        }
    }
}