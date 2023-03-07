<?php

namespace App\Core\Repository;

use App\Core\Entity\Structure;
use Doctrine\Persistence\ManagerRegistry;

class StructureRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->class = Structure::class;
        parent::__construct($registry);
    }

    public function getStructureByFamilyId($familyId, $deleted = false)
    {
        return $this->createQueryBuilder('s')
            ->where('s.family = :familyId')
            ->andWhere('s.deleted = :deleted')
            ->setParameter('deleted', $deleted)
            ->setParameter('familyId', $familyId)
            ->getQuery()
            ->getResult();
    }
}