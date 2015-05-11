<?php

namespace App\Model;


use LeanMapper\Fluent;
use Nette\Object;

class Filters extends Object {
    /**
     * @param Fluent $fluent
     */
    public function orderItems(Fluent $fluent) {
        $fluent->orderBy('[order] ASC');
    }

    /**
     * @param Fluent $fluent
     */
    public function unfinishedItems(Fluent $fluent) {
        $fluent->where('[finished] = 0');
    }
}