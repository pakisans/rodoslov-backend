<?php

namespace App\Core\API\V1;

use App\Core\Entity\Family;
use App\Core\Entity\Sheets;
use App\Core\Entity\Structure;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StructureHandler extends BaseHandler {
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->class = Structure::class;
        $this->idSelector = 'id';
        parent::__construct($em, $container, $logger);
    }

    public function add(){
        $structure = new Structure();

        if(!isset($this->params->familyId) || !isset($this->params->subordinateId)){
            return $this->getParameterMissingResponse();
        }

        $family = $this->em->getRepository(Family::class)->get($this->params->familyId);
        $structure->setFamily($family);

        $superior = $this->em->getRepository(Sheets::class)->get($this->params->superiorId);
        $subordinate = $this->em->getRepository(Sheets::class)->get($this->params->subordinateId);

        if(!isset($this->params->superiorId) && !$superior->getIsStructure()){
            $structure->setSubordinate($subordinate);
        }else{
            $structure->setSubordinate($subordinate);
            $structure->setSuperior($superior);
        }
        $this->em->persist($structure);
        $this->em->flush();
    }

    public function edit(){

    }
}