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

    public function searchSheets(){
        $query = $this->getParameter('query');

        $sheetsRepo = $this->em->getRepository($this->class)->searchSheets($query);

        return $this->getResponse([
            'result' => $sheetsRepo
        ]);
    }

    public function getSheetByFamily(){
        $familyId = $this->getParameter('familyId');
        $superiorId = $this->getParameter('superiorId');
        if(isset($superiorId)){
            $sheets = $this->em->getRepository($this->class)->getSubordinates($familyId,$superiorId);
            return $this->getResponse(['result' => $sheets]);
        }

        $sheets = $this->em->getRepository($this->class)->getSheetByFamilyId($familyId);


        return $this->getResponse(['result' => $sheets]);
    }

    public function add(){
        if(!isset($this->params->firstName) || !isset($this->params->currentLevel)  || !isset($this->params->dateOfBirth) ||
               !isset($this->params->familyId)){
            return $this->getParameterMissingResponse();
        }

        $entity = new Sheets();

        $entity->setFirstName($this->params->firstName);
        $entity->setAddress($this->params->address);
        $entity->setDateOfBirth(new \DateTime($this->params->dateOfBirth));
        $entity->setIsStructure($this->params->isStructure);
        $entity->setCurrentLevel($this->params->currentLevel);

        if(isset($this->params->dateOfDeath)){
            $entity->setDateOfDeath(new \DateTime($this->params->dateOfDeath));
        }else{
            $entity->setDateOfDeath(null);
        }

        if(isset($this->params->photo)){
            $entity->setPhoto($this->params->photo);
        }

        $family = $this->em->getRepository(Family::class)->get($this->params->familyId);
        $entity->setFamily($family);

        $this->em->persist($entity);
        $this->em->flush();

        return $this->getCreatedResponse(['response' => $entity]);
    }

    public function edit(){
        $id = $this->params->id;

        if(!isset($this->params->firstName) || !isset($this->params->currentLevel)  || !isset($this->params->dateOfBirth) ||
            !isset($this->params->familyId)){
            return $this->getParameterMissingResponse();
        }

        $sheet = $this->em->getRepository($this->class)->get($id);

        $sheet->setFirstName($this->params->firstName);
        $sheet->setAddress($this->params->address);
        $sheet->setDateOfBirth(new \DateTime($this->params->dateOfBirth));
        $sheet->setIsStructure($this->params->isStructure);
        $sheet->setCurrentLevel($this->params->currentLevel);
        if(isset($this->params->dateOfDeath)){
            $sheet->setDateOfDeath(new \DateTime($this->params->dateOfDeath));
        }else{
            $sheet->setDateOfDeath(null);
        }

        if(isset($this->params->photo)){
            $sheet->setPhoto($this->params->photo);
        }

        $family = $this->em->getRepository(Family::class)->get($this->params->familyId);
        $sheet->setFamily($family);

        $this->em->flush();

        return $this->getResponse([
            'sheet'=> $sheet
        ]);
    }
}