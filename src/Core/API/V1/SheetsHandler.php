<?php

namespace App\Core\API\V1;

use App\Core\Entity\Family;
use App\Core\Entity\Sheets;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SheetsHandler extends BaseHandler {
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->idSelector = 'id';
        $this->class = Sheets::class;
        parent::__construct($em, $container, $logger);
    }

    public function getSheetsList(){
        $familyId = $this->getParameter('familyId');

        $sheets = $this->em->getRepository($this->class)->getSheetByFamily($familyId);

        return $this->getResponse(['result' => $sheets]);
    }

    public function add(){
        if(!isset($this->params->firstName) || !isset($this->params->currentLevel) || !isset($this->params->isStructure) || !isset($this->params->isValid) || !isset($this->params->dateOfBirth) ||
              !isset($this->params->firstName) || !isset($this->params->familyId)){
            return $this->getParameterMissingResponse();
        }

        $entity = new Sheets();

        $entity->setFirstName($this->params->firstName);
        $entity->setAddress($this->params->address);
        $entity->setDateOfBirth(new \DateTime($this->params->dateOfBirth));

        if(isset($this->params->dateOfDeath)){
            $entity->setDateOfDeath(new \DateTime($this->params->dateOfDeath));
        }

        $entity->setPhoto($this->params->photo);

        $family = $this->em->getRepository(Family::class)->get($this->params->familyId);
        $entity->setFamily($family);


        $this->em->persist($entity);
        $this->em->flush();

        return $this->getCreatedResponse(['response' => $entity]);
    }

    public function edit(){
        $id = $this->idSelector;

        if(!isset($this->params->firstName) || !isset($this->params->currentLevel) || !isset($this->params->isStructure) || !isset($this->params->isValid) || !isset($this->params->dateOfBirth) ||
              !isset($this->params->firstName) || !isset($this->params->familyId)){
            return $this->getParameterMissingResponse();
        }
    }
}