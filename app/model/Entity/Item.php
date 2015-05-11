<?php

namespace App\Model\Entity;

// TODO: add before insert trigger to fix ordering after insert

/**
 * @property    int     $id
 * @property    string  $title
 * @property    int     $finished
 * @property    int     $order
 * @property    User    $user   m:hasOne
 */
class Item extends BaseEntity {

}