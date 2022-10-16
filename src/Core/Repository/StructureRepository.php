<?php

namespace App\Core\Repository;

use App\Core\Entity\Structure;
use Doctrine\Persistence\ManagerRegistry;

class StructureRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry)
    {
        $this->class = Structure::class;
        parent::__construct($registry);
    }
}