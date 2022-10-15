<?php

namespace App\Core\API\V1;

use App\Core\Entity\Family;
use App\Core\Enumeration\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FamilyHandler extends BaseHandler {
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        parent::__construct($em, $container, $logger);
        $this->idSelector = 'id';
        $this->class = Family::class;
    }

    public function add(){
        $loggedUser = $this->getUser();

        if($loggedUser->getUserType() == UserType::GUEST){
            return $this->getForbiddenResponse();
        }

        if(!isset($this->params->familyName)){
            return $this->getParameterMissingResponse();
        }

        $family = new Family();

        $family->setNameOfFamily($this->params->familyName);

        $this->em->persist($family);
        $this->em->flush();

        return $this->getCreatedResponse();
    }

    public function edit(){
        $id = $this->getParameter($this->idSelector);

        $family = $this->em->getRepository(Family::class)->get($id);

        if($this->getUser()->getUserType() == UserType::GUEST){
            return $this->getForbiddenResponse();
        }

        if(!$family){
            return $this->getNotFoundResponse();
        }

        if(!isset($this->params->familyName)){
            return $this->getParameterMissingResponse();
        }

        $family->setFamilyName($this->params->familyName);

        $this->em->flush();

        return $this->getResponse([
            'family' => $family
        ]);
    }
}
