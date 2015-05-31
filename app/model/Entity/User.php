<?php

namespace App\Model\Entity;

/**
 * @property int        $id
 * @property string     $username       nickname
 * @property string     $password  password hash
 * @property Item[]     $items m:belongsToMany(:item)   user items
 */
class User extends BaseEntity {
}
