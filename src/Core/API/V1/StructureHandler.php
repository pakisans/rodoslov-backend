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

    public function getParentAndSlibings(){
        $personId = $this->getParameter('personId');
        $familyId = $this->getParameter('familyId');
        $parent = $this->em->getRepository($this->class)->getParent($personId, $familyId);
        $slibings = [];

        $slibingsRepo = $this->em->getRepository($this->class)->getSlibings($personId, $familyId);
        if(isset($slibingsRepo)){
            foreach ($slibingsRepo as $item){
                array_push($slibings, $item->getSubordinate());
            }
        }

        return $this->getResponse([
            'result' => $parent,
            'slibings' => $slibings
        ]);
    }

    public function getRootByFamily(){
        $rootElement = $this->em->getRepository(Sheets::class)->getRootSheet($this->getParameter('familyId'));
        $structures = $this->em->getRepository($this->class)->getChildrenOfRoot($rootElement ? $rootElement[0]->getId() : []);
        $childrens = [];

        foreach ($structures as $item) {
            array_push($childrens, $item->getSubordinate());
        }

        return $this->getResponse([
            'root' => $rootElement,
            'childrens' => $childrens
        ]);
    }

    public function getChildrens(){
        $parentId = $this->getParameter('id');
        $familyId = $this->getParameter('family');

        $childrensRepo = $this->em->getRepository($this->class)->getChildrens($parentId, $familyId);

        $childrens = [];

        if(isset($childrensRepo)){
            foreach ($childrensRepo as $item) {
                array_push($childrens, $item->getSubordinate());
            }
        }

        return $this->getResponse([
            "childrens" => $childrens
        ]);
    }

    public function add(){
        $structure = new Structure();

        if(!isset($this->params->familyId) || !isset($this->params->subordinateId) || !isset($this->params->superiorId)){
            return $this->getParameterMissingResponse();
        }

        $family = $this->em->getRepository(Family::class)->get($this->params->familyId);
        $structure->setFamily($family);

        $superior = $this->em->getRepository(Sheets::class)->get($this->params->superiorId);
        $subordinate = $this->em->getRepository(Sheets::class)->get($this->params->subordinateId);

//        if($superior->getIsStructure()){
//            return $this->getSubordinateError();
//        }

//        $subordinate->setIsStructure(true);
        $superior->setIsStructure(true);

        $structure->setSuperior($superior);
        $structure->setSubordinate($subordinate);
        $structure->setVersion('v1');
        $structure->setFromDate(new \DateTime());
        $structure->setToDate(new \DateTime());
//        if(!isset($this->params->superiorId) && !$superior->getIsStructure()){
//            $structure->setSubordinate($subordinate);
//        }else{
//            $structure->setSubordinate($subordinate);
//            $structure->setSuperior($superior);
//        }
        $this->em->persist($structure);
        $this->em->flush();

        return $this->getCreatedResponse([
            'result' => $structure
        ]);
    }

    public function edit(){
        $id = $this->params->id;

        if(!isset($this->params->version) || !isset($this->params->currentLevel)  || !isset($this->params->dateOfBirth) ||
            !isset($this->params->familyId)){
            return $this->getParameterMissingResponse();
        }

        $structure = $this->em->getRepository($this->class)->get($id);
    }

    public function deleteStructure() {

        $id = $this->getParameter($this->idSelector);

        if(!isset($id)) {
            return $this->getParameterMissingResponse();
        }

        $entity = $this->em->getRepository($this->class)->find($id);
        if(!$entity) {
            return $this->getNotFoundResponse();
        }

        $familyId = $entity->getFamily()->getId();
        $subordinateId = $entity->getSubordinate()->getId();

        $isParent = $this->em->getRepository($this->class)->isSubordinateParent($familyId, $subordinateId);

        if($isParent){
            return $this->getParentDeleteErrorResponse();
        }

        $entity->setDeleted(true);

        $this->em->flush();

        return $this->getNoContentResponse();
    }
}
