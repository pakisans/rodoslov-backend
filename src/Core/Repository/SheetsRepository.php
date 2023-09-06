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

    public function searchSheets($query, $deleted = false) {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->leftJoin('s.family', 'f')
            ->andWhere('f.nameOfFamily LIKE :query OR s.address LIKE :query OR s.firstName LIKE :query OR s.dateOfBirth LIKE :query')
            ->setParameter('query', '%' . $query . '%');
            $qb->andWhere('s.deleted = :deleted')
            ->setParameter('deleted', false);

        return $qb->getQuery()->getResult();
    }

    public function getRootSheet ($familyId, $deleted = false) {

        $superiorIds = $this->createQueryBuilder('s')
        ->select('s.id')
        ->from('App\Core\Entity\Structure', 'ss')
        ->where('ss.family =:familyId')
        ->andWhere('s.id = ss.superior')
        ->andWhere('ss.deleted = :deleted')
        ->setParameter('deleted', $deleted)
        ->setParameter('familyId', $familyId)
        ->getQuery()
        ->getResult();

        $subordinateIds = $this->createQueryBuilder('p')
        ->select('p.id')
        ->from('App\Core\Entity\Structure', 'pp')
        ->where('pp.family =:familyId')
        ->andWhere('p.id = pp.subordinate')
        ->andWhere('pp.deleted = :deleted')
        ->setParameter('deleted', $deleted)
        ->setParameter('familyId', $familyId)
        ->getQuery()
        ->getResult();

        return $this->createQueryBuilder('s')
            ->where('s.id IN (:superiorIds)')
            ->andWhere('s.id NOT IN (:subordinateIds)')
            ->andWhere('s.deleted = :deleted')
            ->andWhere('s.family = :familyId')
            ->setParameters([
                'subordinateIds' => $subordinateIds,
                'superiorIds' => $superiorIds,
                'deleted' => $deleted,
                'familyId' => $familyId,
            ])
            ->getQuery()
            ->getResult();
    }
}
