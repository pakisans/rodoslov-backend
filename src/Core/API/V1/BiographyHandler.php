<?php

namespace App\Core\API\V1;

use App\Core\Entity\Biography;
use App\Core\Entity\Sheets;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BiographyHandler extends BaseHandler {
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->class = Biography::class;
        $this->idSelector = 'id';
        parent::__construct($em, $container, $logger);
    }

    public function add(){
        if(!isset($this->params->dateFrom) || !isset($this->params->whereIs) || !isset($this->params->sheetId) ||
            !isset($this->params->biographyDescription)){
            return $this->getParameterMissingResponse();
        }
        $biography = new Biography();
        $biography->setDateFrom(new \DateTime($this->params->dateFrom));
        if(isset($this->params->dateTo)){
            $biography->setDateTo(new \DateTime($this->params->dateTo));
        }
        $biography->setBiographyDescription($this->params->biographyDescription);
        $biography->setGraveMarker($this->params->graveMarker);
        $biography->setSpouseInformation($this->params->spouseInformation);
        $biography->setWhereIs($this->params->whereIs);

        $sheet = $this->em->getRepository(Sheets::class)->get($this->params->sheetId);
        $biography->setSheets($sheet);

        $this->em->persist($biography);
        $this->em->flush();

        return $this->getCreatedResponse();
    }

    public function edit(){
        if(!isset($this->params->dateFrom) || !isset($this->params->whereIs) || !isset($this->params->sheetId) ||
            !isset($this->params->biographyDescription)){
            return $this->getParameterMissingResponse();
        }
        $biography = $this->em->getRepository($this->class)->get($this->params->id);

        if(isset($this->params->dateTo)){
            $biography->setDateTo(new \DateTime($this->params->dateTo));
        }
        $biography->setDateFrom(new \DateTime($this->params->dateFrom));
        $biography->setBiographyDescription($this->params->biographyDescription);
        $biography->setGraveMarker($this->params->graveMarker);
        $biography->setSpouseInformation($this->params->spouseInformation);
        $biography->setWhereIs($this->params->whereIs);

        $sheet = $this->em->getRepository(Sheets::class)->get($this->params->sheetId);
        $biography->setSheets($sheet);
        $this->em->flush();

        return $this->getSuccessResponse([
            'result' => $biography
        ]);
    }

    public function getBiographyByNode(){
        $id = $this->getParameter('id');
        if($id == 'root'){
            $id = $this->getParameter('mainId');
        }

        return $this->getResponse([
            'result' => $this->em->getRepository($this->class)->getNode($id),
            'id' => $id
        ]);
    }
}