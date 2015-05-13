<?php

namespace App\Model\Repository;


use App\Model\Entity\Item;

class ItemRepository extends BaseRepository {

    /**
     * Inserts item to index.
     *
     * @param Item $item
     * @param int $index
     * @throws \Exception
     */
    public function insertAt(Item $item, $index) {
        $this->connection->begin();
        try {
            if ($index > $item->order) {
                // moving forward
                // all items between actual item order and new index are moved backward and item order is set to new index
                $this->connection->query('
              UPDATE [item]
              SET
                [ORDER] = [ORDER]-1
              WHERE
                  [user_id] = %i
                AND
                  [ORDER] > %i
                AND
                  [ORDER] <= %i
                AND
                  [finished] = 0',
                    $item->user->id, $item->order, $index);
            } elseif ($index < $item->order) {
                // moving backward
                // all items between actual order and new index are moved forward and item inserted to empty place
                $this->connection->query('
              UPDATE [item]
              SET
                [ORDER] = [ORDER]+1
              WHERE
                  [user_id] = %i
                AND
                  [ORDER] >= %i
                AND
                  [ORDER] < %i
                AND
                  [finished] = 0',
                    $item->user->id, $index, $item->order);
            }
            $item->order = $index;
            $this->persist($item);
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
        $this->connection->commit();
    }
}
