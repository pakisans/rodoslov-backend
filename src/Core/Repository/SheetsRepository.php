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

    public function getSheetByFamily($familyId, $deleted = false){
        return $this->createQueryBuilder('s')
            ->where('s.family = :familyId')
            ->andWhere('s.deleted = :deleted')
            ->setParameter('deleted', $deleted)
            ->setParameter('familyId', $familyId)
            ->getQuery()
            ->getResult();
    }
}
