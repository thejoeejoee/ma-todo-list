<?php

namespace App\Presenters;


use App\Components\CssComponentFactory;
use App\Components\IUserComponentFactory;
use App\Components\JsComponentFactory;
use App\Components\UserComponent;
use Nette\Application\UI\Presenter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

class BasePresenter extends Presenter {

    /** @var JsComponentFactory @inject */
    public $JsComponentFactory;

    /** @var CssComponentFactory @inject */
    public $CssComponentFactory;

    /** @var IUserComponentFactory @inject */
    public $UCF;

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

    /**
     * @return JavaScriptLoader
     */
    protected function createComponentJsComponent() {
        return $this->JsComponentFactory->create();
    }

    /**
     * @return CssLoader
     */
    protected function createComponentCssComponent() {
        return $this->CssComponentFactory->create();
    }

    /**
     * @return UserComponent
     */
    protected function createComponentUserComponent() {
        $userComponent = $this->UCF->create($this->user);
        $userComponent->onLogin[] = function () {
            $this->flashMessage('Příhlášení proběhlo úspěšně', 'success');
            $this->redirect('Homepage:default');
        };
        $userComponent->onLogout[] = function () {
            $this->flashMessage('Uživatel odhlášen!', 'success');
            $this->redirect('Homepage:default');
        };
        return $userComponent;
    }

    protected function afterRender() {
        parent::afterRender();
        $this->redrawControl('flashes');
    }

}