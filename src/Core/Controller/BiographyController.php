<?php

namespace App\Core\Controller;

use App\Core\API\Factory\ApiFactory;
use App\Core\API\HandlerType;
use App\Core\Service\AuthorizationService;
use Symfony\Component\HttpFoundation\Request;

class BiographyController extends BaseEntityController {
    public function __construct(ApiFactory $apiFactory, AuthorizationService $authorizationService)
    {
        $this->handlerType = HandlerType::Biography;
        parent::__construct($apiFactory, $authorizationService);
    }

    public function getBiographyByNodeAction(Request  $request){
        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->getBiographyByNode();
    }
}