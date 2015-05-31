<?php

namespace App\Presenters;

use App\Components\ITodoListComponentFactory;
use App\Components\TodoListComponent;
use App\Model\Repository\UserRepository;


class HomepagePresenter extends BasePresenter {

    /** @var ITodoListComponentFactory @inject */
    public $TLCF;

    /** @var UserRepository @inject */
    public $UR;

    /**
     * @return TodoListComponent
     */
    public function createComponentTodoList() {
        return $this->TLCF->create($this->UR->get($this->user->id));
    }

    protected function startup() {
        parent::startup();
        if (!$this->user->loggedIn) {
            $this->redirect('Sign:in');
        }
    }


}
