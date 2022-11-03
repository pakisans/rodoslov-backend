<?php

namespace App\Core\Controller;

use App\Core\API\Factory\ApiFactory;
use App\Core\API\HandlerType;
use App\Core\Service\AuthorizationService;

class BiographyController extends BaseEntityController {
    public function __construct(ApiFactory $apiFactory, AuthorizationService $authorizationService)
    {
        $this->handlerType = HandlerType::Biography;
        parent::__construct($apiFactory, $authorizationService);
    }
}