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
    public function getChildrenOfRoot($rootElement, $deleted = false){
        return $this->createQueryBuilder('s')
            ->where('s.superior = :id')
            ->andWhere('s.deleted = :deleted')
            ->setParameter('deleted', $deleted)
            ->setParameter('id', $rootElement)
            ->getQuery()
            ->getResult();
    }

    public function getParent ($id,$familyId, $deleted = false) {
        $superior = $this->createQueryBuilder('s')
            ->where('s.family =:familyId')
            ->andWhere('s.subordinate =:id')
            ->andWhere('s.deleted =:deleted')
            ->setParameter('deleted', $deleted)
            ->setParameter('familyId', $familyId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if($superior){
           return $superior->getSuperior();
        }
        return null;
    }

    public function getSlibings ($id, $familyId, $deleted = false){
        $parent = $this->getParent($id,$familyId);
        if(!$parent) return null;
        $parent = $parent->getId();

        return $this->createQueryBuilder('s')
            ->where('s.family =:familyId')
            ->andWhere('s.deleted =:deleted')
            ->andWhere('s.superior =:superiorId')
            ->setParameter('deleted', $deleted)
            ->setParameter('familyId', $familyId)
            ->setParameter('superiorId', $parent)
            ->getQuery()
            ->getResult();
    }

    public function isSubordinateParent($familyId, $subordinateId, $deleted = false)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder
            ->select('COUNT(s.id)')
            ->where('s.family = :familyId')
            ->andWhere('s.superior = :subordinateId')
            ->andWhere('s.deleted = :deleted')
            ->setParameters([
                'familyId' => $familyId,
                'subordinateId' => $subordinateId,
                'deleted' => $deleted
            ]);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        return ($result !== null && $result[1] > 1);
    }

    public function getChildrens($parent, $family, $deleted = false)
    {
        if (empty($parent) || empty($family)) {
            throw new InvalidArgumentException('Parent and family IDs are required.');
        }

        try {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder
                ->where('s.superior = :parent')
                ->andWhere('s.family =:family')
                ->andWhere('s.deleted =:deleted')
                ->setParameters([
                    'parent' => $parent,
                    'family' => $family,
                    'deleted' => $deleted
                ]);

            $result = $queryBuilder->getQuery()->getResult();
        } catch (Exception $e) {
            throw new RuntimeException('An error occurred while retrieving family members: ' . $e->getMessage());
        }

        return $result;
    }
}