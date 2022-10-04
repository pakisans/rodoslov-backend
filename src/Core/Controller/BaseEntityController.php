<?php

namespace App\Core\Controller;

use Symfony\Component\HttpFoundation\Request;

class BaseEntityController extends BaseController {
    protected $handlerType;

    public function getlistAction(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->getList();
    }

    public function getAllAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->getAll();
    }

    public function getAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->get();
    }

    public function addAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->add();
    }

    public function editAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->edit();
    }

    public function deleteAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $handler = $this->getHandler($request, $this->handlerType);
        return $handler->delete();
    }
}