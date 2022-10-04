<?php

namespace App\Core\Controller;

use App\Core\API\Factory\ApiFactory;
use App\Core\Service\AuthorizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController {
    private $apiFactory;
    protected $authorizationService;

    function __construct(ApiFactory $apiFactory, AuthorizationService $authorizationService) {
        $this->apiFactory = $apiFactory;
        $this->authorizationService = $authorizationService;
    }

    protected function getSerializer()
    {
        return $this->get('jms_serializer');
    }

    protected function getHandler(Request $request, $type, $admin = false) {
        $handler = $this->apiFactory->getHandler($request, $type, $admin);
        $handler->setRequest($request);
        return $handler;
    }

    protected function accessNotAllowed() {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}