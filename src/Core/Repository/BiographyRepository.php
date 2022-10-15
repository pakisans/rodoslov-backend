<?php

namespace App\Core\Repository;

use App\Core\Entity\Biography;
use Doctrine\Persistence\ManagerRegistry;

class BiographyRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry)
    {
        $this->class = Biography::class;
        parent::__construct($registry);
    }
}