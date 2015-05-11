<?php

namespace App\Components;


use App\Model\Entity\Item;
use App\Model\Repository\ItemRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class TodoItemComponent extends BaseComponent {

    /** @var Item|NULL */
    private $item;

    /** @var ItemRepository */
    private $IR;

    public function __construct(Item $item = NULL, ItemRepository $IR) {
        $this->item = $item;
        $this->IR = $IR;
    }

    /***/
    public function render() {
        $this->template->item = $this->item;
        if (!$this->item) {
            $this->template->edit = TRUE;
        }
        parent::render();
    }

    public function handleEdit() {
        $this->template->edit = TRUE;
        if ($this->isAjax()) {
            $this->redrawControl('item');
        }
    }

    /**
     * @return Form
     */
    public function createComponentItemForm() {
        $f = new Form();
        $title = $f->addText('title', 'Titulek');
        if ($this->item) {
            $title->setValue($this->item->title);
        }
        $f->addSubmit('submit', $this->item ? 'Uložit' : "Přidat");
        $f->onSuccess[] = function ($form, $values) {
            $this->itemFormSucceed($form, $values);
        };
        return $f;
    }

    /***/
    public function itemFormSucceed(Form $form, ArrayHash $values) {
        if ($this->isAjax()) {
            $this->redrawControl('item');
        }
        $this->presenter->flashMessage($this->item ? 'Aktualizováno!' : "Úspěšně přidáno!", 'success');
    }
}

interface ITodoItemComponentFactory {
    /**
     * @param Item|NULL $item
     * @return TodoItemComponent
     */
    public function create(Item $item = NULL);
}