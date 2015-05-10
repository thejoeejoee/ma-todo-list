<?php

namespace App\Presenters;


use App\Components\JsComponentFactory;
use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter {

    /** @var JsComponentFactory @inject */
    public $JsComponentFactory;

    protected function createComponentJsComponent() {
        return $this->JsComponentFactory->create();
    }

    protected function afterRender() {
        parent::afterRender();
        $this->redrawControl('flashes');
    }

}