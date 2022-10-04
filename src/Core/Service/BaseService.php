<?php

namespace App\Core\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class BaseService {
    protected $em;
    protected $container;
    protected $logger;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container,
                                LoggerInterface $logger) {
        $this->em = $em;
        $this->container = $container;
        $this->logger = $logger;
    }

    public function getUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if ($token !== null)
        {
            return $token->getUser() === 'anon.' ? null : $token->getUser();
        }
        else
        {
            return null;
        }
    }
}