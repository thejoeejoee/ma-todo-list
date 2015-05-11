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

    /**
     * Formats layout template file names.
     * @return array
     */
    public function formatLayoutTemplateFiles() {
        $layout = $this->layout ? $this->layout : 'layout';
        $dir = $this->context->parameters['appDir'];
        $presenter = $this->getName();
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        $list = array(
            "$dir/templates/$presenter/@$layout.latte",
            "$dir/templates/$presenter.@$layout.latte",
            "$dir/templates/@$layout.latte",
        );
        return $list;
    }


    /**
     * Formats view template file names.
     * @return array
     */
    public function formatTemplateFiles() {
        $dir = $this->context->parameters['appDir'];
        $presenter = $this->getName();
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        $list = array(
            "$dir/templates/$presenter.$this->view.latte",
            "$dir/templates/$presenter.$this->view.latte",
            "$dir/templates/$presenter/$this->view.latte",
        );
        return $list;
    }

}