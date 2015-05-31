<?php

namespace App\Presenters;


class SignPresenter extends BasePresenter {

    protected function startup() {
        parent::startup();
        if ($this->user->loggedIn) {
            $this->redirect('Homepage:default');
        }
    }
}
