<?php

namespace App\Core\Repository;

use App\Core\Entity\Family;
use Doctrine\Persistence\ManagerRegistry;

class FamilyRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry)
    {
        $this->class = Family::class;
        parent::__construct($registry);
    }
}