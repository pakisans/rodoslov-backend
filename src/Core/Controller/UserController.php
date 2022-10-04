<?php

namespace App\Core\Controller;

use App\Core\API\HandlerType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends \App\Core\Controller\BaseEntityController {
    public function __construct(\App\Core\API\Factory\ApiFactory $apiFactory, \App\Core\Service\AuthorizationService $authorizationService){
        parent::__construct($apiFactory, $authorizationService);

        $this->handlerType = HandlerType::User;
    }
    public function getCurrentUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, HandlerType::User);
        $user = $handler->getCurrentUser();

        return $user;
    }

    public function registrationAction(Request $request) {

        $handler = $this->getHandler($request, HandlerType::User);

        return $handler->registration();
    }

    public function activateAction(Request $request) {

        $handler = $this->getHandler($request, HandlerType::User);

        return $handler->activate();
    }

    public function getTokenAction(Request $request) {

        $handler = $this->getHandler($request, HandlerType::User);

        return $handler->getToken();
    }

    public function resetPasswordAction(Request $request)
    {
        $handler = $this->getHandler($request, HandlerType::User);

        return $handler->resetPassword();
    }

    public function resetPasswordFormAction(Request $request)
    {
        $handler = $this->getHandler($request, HandlerType::User);

        return $handler->resetPasswordForm();
    }

}