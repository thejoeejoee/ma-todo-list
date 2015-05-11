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
     * @param array $items
     * @param User $user
     * @param ItemRepository $IR
     * @param ITodoItemComponentFactory $TICF
     */
    public function __construct(User $user, ItemRepository $IR, ITodoItemComponentFactory $TICF) {
        $this->IR = $IR;
        $this->TICF = $TICF;
        $this->user = $user;
        $this->items = $user->items;
    }

    /***/
    public function render()
    {
        $this->template->items = $this->items;
        parent::render();
    }

    /**
     * @return Multiplier
     */
    protected function createComponentTodoItemComponent()
    {
        return new Multiplier(function ($id) {
            $todoItem = $this->TICF->create($this->IR->get($id), $this->user);
            /** @var Form $form */
            $form = $todoItem['itemForm'];
            $form->onSuccess[] = function () {
                if ($this->isAjax()) {
                    $this->redrawControl('items');
                }
            };
            return $todoItem;
        });
    }
}

interface ITodoListComponentFactory {
    /**
     * @param User $user
     * @return TodoListComponent
     */
    public function create(User $user);
}