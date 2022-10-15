<?php

namespace App\Core\API\V1;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseHandler {
    protected $em;
    protected $container;
    protected $serializer;
    protected $user;
    protected $logger;
    protected $params;
    protected $request;
    protected $idSelector;

    function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger){
        $this->em = $em;
        $this->container = $container;
        $this->logger = $logger;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
        $this->params = $this->getParams($request);
    }

    public function getParams(Request $request) {
        $params = array();
        $content = $request->getContent();

        if(!empty($content))
        {
            $params = json_decode($content);
        }

        return $params;
    }

    protected function getResponse($result = [], $statusCode = Response::HTTP_OK) {
        $response = $this->createResponse($statusCode);

        $json = $this->getSerializer()->serialize($result, 'json');
        $response->setData(json_decode($json, true));

        return $response;
    }

    protected function createResponse($statusCode = Response::HTTP_OK) {
        $response = new JsonResponse();
        $response->setStatusCode($statusCode);
        $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('allowed_origins'));
        return $response;
    }

    public function getUser() {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    public function getParameter($paramName) {

        return $this->request->get($paramName);
    }

    public function getSerializer() {

        if(!$this->serializer) {
            $this->serializer = $this->container->get('jms_serializer');
        }

        return $this->serializer;
    }

    /* RESPONSES  */

    public function getSuccessResponse() {
        return $this->getResponse([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    public function getErrorResponse($message)
    {
        return $this->getResponse([
            'message' => $message
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getParentDeleteErrorResponse()
    {
        return $this->getResponse([
            'message' => 'Parent Can NOT Be Deleted. Parent Has Children. That is not alowed in DELETE method.'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getParameterMissingResponse()
    {
        return $this->getResponse([
            'message' => 'parametersMissing'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getNotFoundResponse() {
        return $this->getResponse([], Response::HTTP_NOT_FOUND);
    }

    public function getNoContentResponse() {
        return $this->getResponse([], Response::HTTP_NO_CONTENT);
    }

    public function getCreatedResponse() {
        return $this->getResponse([], Response::HTTP_CREATED);
    }

    public function getForbiddenResponse() {
        return $this->getResponse([
            'message' => 'permissionMissing'
        ], Response::HTTP_FORBIDDEN);
    }

    // FUNCTIONALITIES

    public function delete() {

        $id = $this->getParameter($this->idSelector);

        if(!isset($id)) {
            return $this->getParameterMissingResponse();
        }

        $entity = $this->em->getRepository($this->class)->find($id);

        if(!$entity) {
            return $this->getNotFoundResponse();
        }

        $entity->setDeleted(true);

        $this->em->flush();

        $this->afterDelete($entity);

        return $this->getNoContentResponse();
    }

    public function get() {
        $id = $this->getParameter($this->idSelector);

        if(!isset($id)) {
            return $this->getParameterMissingResponse();
        }

        $entity = $this->em->getRepository($this->class)->get($id);

        if(!$entity) {
            return $this->getNotFoundResponse();
        }

        return $this->getResponse([
            'entity' => $entity
        ]);
    }

    public function getAll() {
        return $this->getResponse([
            'result' => $this->em->getRepository($this->class)->getAll()
        ]
        );
    }

    protected function trim($value) {
        return preg_replace('/\s+/', ' ', trim($value));
    }

}