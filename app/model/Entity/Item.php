<?php

namespace App\Model\Entity;

/**
 * @property    int     $id
 * @property    string  $title
 * @property    int     $finished
 * @property    int     $order
 * @property    User    $user   m:belongsToOne
 */
class Item extends BaseEntity {

}