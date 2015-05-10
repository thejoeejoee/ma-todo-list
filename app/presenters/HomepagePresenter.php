<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends BasePresenter {

    public function renderDefault() {
    }

    public function handleTest() {
        $this->flashMessage('huhu');
    }
}
