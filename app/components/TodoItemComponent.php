<?php

namespace App\Components;


use App\Model\Entity\Item;
use App\Model\Entity\User;
use App\Model\Repository\ItemRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class TodoItemComponent extends BaseComponent {

    /** @var Item|NULL */
    private $item;

    /** @var User|NULL */
    private $user;

    /** @var ItemRepository */
    private $IR;

    public function __construct(Item $item = NULL, User $user, ItemRepository $IR) {
        $this->item = $item;
        $this->IR = $IR;
        $this->user = $user;
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
        $title = $f->addText('title', 'Titulek')->setRequired('Název je opravdu důležitý, vyplň ho, prosím.');
        if ($this->item) {
            $title->setValue($this->item->title);
        }
        $f->addSubmit('submit', $this->item ? 'Uložit' : "Přidat");
        $f->onSuccess[] = function ($form, $values) {
            $this->itemFormSucceed($form, $values);
        };
        return $f;
    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     */
    public function itemFormSucceed(Form $form, ArrayHash $values) {
        $item = $this->item ? $this->item : new Item();
        $item->assign($values);
        $item->user = $this->user;
        $item->order = $item->user->items ? (max(array_map(function (Item $item) { return $item->order; }, $item->user->items)) + 1) : 1;
        $this->IR->persist($item);
        $this->presenter->flashMessage($this->item ? 'Aktualizováno!' : "Úspěšně přidáno!", 'success');
        if ($this->isAjax()) {
            $this->redrawControl('item');
            // TODO: fix rendering new item
            $this->parent->parent->redrawControl('items');
        }
    }
}

interface ITodoItemComponentFactory {
    /**
     * @param Item|NULL $item
     * @param User $user
     * @return TodoItemComponent
     */
    public function create(Item $item = NULL, User $user);
}