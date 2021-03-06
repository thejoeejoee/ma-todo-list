<?php

namespace App\Model\Repository;

use App\Model\Entity\BaseEntity;
use LeanMapper\Entity;
use LeanMapper\Repository;

abstract class BaseRepository extends Repository {

    /** constants for ordering options  */
    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    /** constants for selecting from array of conditions */
    const METHOD_OR = 'OR';
    const METHOD_AND = 'AND';

    /**
     * @param $id
     * @param bool $userFilters
     * @return BaseEntity|NULL
     */
    public function get($id, $userFilters = TRUE) {
        if ($userFilters) {
            $f = $this->createFluent();
        } else {
            $f = $this->connection->select('[' . $this->getTable() . '.*]')->from($this->getTable());
        }
        $row = $f->where('[' . $this->getTable() . '.id] = %i', $id)->fetch();
        return $row ? $this->createEntity($row) : NULL;
    }

    /**
     * @return Entity[]
     */
    public function findAll() {
        return $this->createEntities(
            $this->createFluent()->fetchAll()
        );
    }

    /**
     * @param int[] $ids
     * @return Entity[]
     */
    public function findByIds(array $ids) {
        if (!$ids) {
            return array();
        }
        $entities = array();
        foreach ($this->createEntities($this->createFluent()->where('[id] IN (%i)', $ids)->fetchAll()) as $entity) {
            $entities[$entity->id] = $entity;
        }
        return $entities;
    }
}
