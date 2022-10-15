<?php

namespace App\Core\Controller;

use App\Core\API\Factory\ApiFactory;
use App\Core\API\HandlerType;
use App\Core\Service\AuthorizationService;

class FamilyController extends BaseEntityController {
    public function __construct(ApiFactory $apiFactory, AuthorizationService $authorizationService){
        parent::__construct($apiFactory, $authorizationService);
        $this->handlerType = HandlerType::Family;
    }
}