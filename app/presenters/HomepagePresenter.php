<?php

namespace App\Presenters;

use App\Components\ITodoListComponentFactory;
use App\Model\Repository\UserRepository;


class HomepagePresenter extends BasePresenter {

    /** @var ITodoListComponentFactory @inject */
    public $TLCF;

    /** @var UserRepository @inject */
    public $UR;

    /**
     * @return \App\Components\TodoListComponent
     */
    public function createComponentTodoList() {
        return $this->TLCF->create($this->UR->get(1));
    }


}
