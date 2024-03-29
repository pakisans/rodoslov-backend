<?php

namespace App\Core\Controller;

use App\Core\API\Factory\ApiFactory;
use App\Core\API\HandlerType;
use App\Core\Service\AuthorizationService;
use Symfony\Component\HttpFoundation\Request;

class SheetsController extends BaseEntityController {
    public function __construct(ApiFactory $apiFactory, AuthorizationService $authorizationService){
        $this->handlerType = HandlerType::Sheets;
        parent::__construct($apiFactory, $authorizationService);
    }

    public function getSheetByFamilyAction(Request  $request){
        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->getSheetByFamily();
    }

    public function searchSheetsAction(Request  $request){
        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->searchSheets();
    }
}