<?php

namespace App\Components;


use App\Model\Entity\Item;
use App\Model\Entity\User;
use App\Model\Repository\ItemRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\InvalidStateException;

interface ITodoListComponentFactory {
    /**
     * @param User $user
     * @return TodoListComponent
     */
    public function create(User $user);
}

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
        $this->template->items = $this->user->items;
        parent::render();
    }

    /**
     * @param int $id
     * @param int $new
     * @throws \Exception
     */
    public function handleInsert($id, $new) {
        /** @var Item $item */
        $item = $this->IR->get($id);
        try {
            $this->IR->insertOn($item, $new, $this);
        } catch (InvalidStateException $e) {
            $this->presenter->flashMessage('Hohoo', 'warning');
        }

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
        $this->IR->finish($item, $finished);
    }

    /**
     * @return Multiplier
     */
    protected function createComponentTodoItemComponent() {
        return new Multiplier(function ($id) {
            /** @var Item $item */
            $item = $this->IR->get($id);
            $todoItem = $this->TICF->create($item, $this->user);
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
}