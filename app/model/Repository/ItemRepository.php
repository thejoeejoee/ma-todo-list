<?php

namespace App\Model\Repository;


use App\Model\Entity\Item;
use Nette\InvalidStateException;

class ItemRepository extends BaseRepository {

    /**
     * @param Item $item
     * @param $new
     * @throws \DibiException
     * @throws \Exception
     */
    public function insertOn(Item $item, $new) {
        $old = $item->order;
        $this->connection->begin();
        try {
            if ($new > $old) {
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
                  [finished] = 0', $item->user->id, $old, $new);

                $item->order = $new;
            } elseif ($new < $old) {
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
                    $item->user->id, $new, $old);
                $item->order = $new;
            } else {
                throw new InvalidStateException();
            }
            $this->persist($item);
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
        $this->connection->commit();
    }

    /**
     * @param Item $item
     * @param bool $finished
     * @throws \DibiException
     * @throws \Exception
     */
    public function finish(Item $item, $finished) {
        $this->connection->begin();
        try {
            if ($finished) {
                $this->connection->query('
              UPDATE [item]
              SET
                [ORDER] = [ORDER]-1
              WHERE
                  [user_id] = %i
                AND
                  [ORDER] > %i
                AND
                  [finished] = 0', $item->user->id, $item->order);
                $item->finished = 1;
            } else {
                $item->order = max(array_map(function (Item $item) {
                        return $item->order;
                    }, $item->user->items)) + 1;
                $item->finished = 0;
            }
            $this->persist($item);
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
        $this->connection->commit();

    }
}
