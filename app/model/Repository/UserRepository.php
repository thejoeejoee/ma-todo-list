<?php

namespace App\Model\Repository;


use App\Model\Entity\User;

class UserRepository extends BaseRepository {
    /**
     * @param $username string
     * @return bool
     */
    public function existsUsername($username) {
        $user = $this->findByUsername($username);
        return $user ? TRUE : FALSE;
    }

    /**
     * @param $username string
     * @return User|NULL
     */
    public function findByUsername($username) {
        $row = $this->createFluent()
            ->where('username = %s', $username)
            ->fetch();

        return $row ? $this->createEntity($row) : NULL;
    }
}
