<?php

namespace App\Components;


use App\Model\Entity\Item;
use App\Model\Repository\ItemRepository;
use Nette\Application\UI\Multiplier;

class TodoListComponent extends BaseComponent {

    /** @var Item[] */
    private $items;

    /** @var ItemRepository */
    private $IR;

    /** @var ITodoItemComponentFactory */
    private $TICF;

    public function __construct(array $items, ItemRepository $IR, ITodoItemComponentFactory $TICF) {
        $this->items = $items;
        $this->IR = $IR;
        $this->TICF = $TICF;
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
            return $this->TICF->create($this->IR->get($id));
        });
    }
}

interface ITodoListComponentFactory {
    /**
     * @param Item[] $items
     * @return TodoListComponent
     */
    public function create(array $items);
}