<?php

namespace App\Presenters;


use App\Components\CssComponentFactory;
use App\Components\JsComponentFactory;
use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter {

    /** @var JsComponentFactory @inject */
    public $JsComponentFactory;

    protected function createComponentJsComponent() {
        return $this->JsComponentFactory->create();
    }

    /** @var CssComponentFactory @inject */
    public $CssComponentFactory;

    protected function createComponentCssComponent() {
        return $this->CssComponentFactory->create();
    }

    protected function afterRender() {
        parent::afterRender();
        $this->redrawControl('flashes');
    }

}