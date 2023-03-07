<?php

namespace App\Core\Repository;

use App\Core\Entity\Sheets;
use Doctrine\Persistence\ManagerRegistry;

class SheetsRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry)
    {
        $this->class = Sheets::class;
        parent::__construct($registry);
    }

    public function getSheetByFamilyId($familyId, $deleted = false){
        return $this->createQueryBuilder('s')
            ->where('s.family = :familyId')
            ->andWhere('s.deleted = :deleted')
            ->orderBy('s.dateOfBirth', 'ASC')
            ->setParameter('deleted', $deleted)
            ->setParameter('familyId', $familyId)
            ->getQuery()
            ->getResult();
    }

    public function getSubordinates($familyId, $superiorId, $deleted = false, $isStructure = false){
        return $this->createQueryBuilder('s')
            ->where('s.family = :familyId')
            ->andWhere('s.isStructure = :isStructure')
            ->andWhere('s.deleted = :deleted')
            ->andWhere('s.id != :superiorId')
            ->orderBy('s.dateOfBirth', 'ASC')
            ->setParameter('deleted', $deleted)
            ->setParameter('isStructure', $isStructure)
            ->setParameter('familyId', $familyId)
            ->setParameter('superiorId', $superiorId)
            ->getQuery()
            ->getResult();
    }
}
