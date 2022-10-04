<?php

namespace App\Core\API\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiFactory {
    private $em;
    private $container;
    private $logger;

    function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger){
        $this->em = $em;
        $this->container = $container;
        $this->logger = $logger;
    }

    public function getHandler(Request $request, $type, $admin = false){
        $version = $this->getVersion($request);

        $handler = null;

        if($version == 1){
            $apiFactory = new ApiHandlerFactoryV1();

            return $apiFactory->getHandler($type, $this->em, $this->container, $this->logger, $admin );
        }
        return $handler;
    }

    protected function getVersion(Request $request)
    {
        $version = 1;

        if($request->headers->has('version'))
        {
            $version = $request->headers->get('version');
        }

        return $version;
    }
}