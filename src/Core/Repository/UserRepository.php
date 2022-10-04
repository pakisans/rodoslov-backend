<?php

namespace App\Core\Repository;

use App\Core\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        $this->class = User::class;

        parent::__construct($registry);
    }

    public function getByEmail($email) {
        return $this->findOneBy([
            'email' => $email
        ]);
    }

    public function getByRegistrationToken($token) {
        return $this->findOneBy([
            'registrationToken' => $token
        ]);
    }
}