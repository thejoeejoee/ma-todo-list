<?php

namespace App\Components;


use App\Model\Entity\Item;
use App\Model\Entity\User;
use App\Model\Repository\ItemRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;

class TodoListComponent extends BaseComponent {

    /** @var Item[] */
    private $items;

    /** @var User */
    private $user;

    /** @var ItemRepository */
    private $IR;

    /** @var ITodoItemComponentFactory */
    private $TICF;

    /**
     * @param User $user
     * @param ItemRepository $IR
     * @param ITodoItemComponentFactory $TICF
     */
    public function __construct(User $user, ItemRepository $IR, ITodoItemComponentFactory $TICF) {
        $this->IR = $IR;
        $this->TICF = $TICF;
        $this->user = $user;
    }

    public function render() {
        $this->user->markAsUpdated();
        $this->template->items = $this->user->items;
        parent::render();
    }

    /**
     * @return Multiplier
     */
    protected function createComponentTodoItemComponent() {
        return new Multiplier(function ($id) {
            $todoItem = $this->TICF->create($this->IR->get($id), $this->user);
            if (!$id) {
                /** @var Form $form */
                $form = $todoItem['itemForm'];
                $form->onSuccess[] = function ($values) {
                    $this->redrawControl('items');
                };
            }
            return $todoItem;
        });
    }

    /**
     * Signal for moving items to new index.
     *
     * @param int $id
     * @param int $index
     */
    public function handleInsert($id, $index) {
        /** @var Item $item */
        $item = $this->IR->get($id);
        $this->IR->insertAt($item, $index);
    }

    /**
     * Signal for (un)finishing todo items.
     *
     * @param int $id
     * @param int $finished
     */
    public function handleFinish($id, $finished = 1) {
        /** @var Item $item */
        $item = $this->IR->get((int)$id, FALSE);
        $item->finished = $finished;
        $this->IR->persist($item);
    }
}

interface ITodoListComponentFactory {
    /**
     * @param User $user
     * @return TodoListComponent
     */
    public function create(User $user);
}