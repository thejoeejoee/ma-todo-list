<?php

namespace App\Components;

use Nette\Application\UI\Control;

abstract class BaseComponent extends Control {

    public function render() {
        $this->template->render();
    }

    /**
     * @return bool
     */
    public function isAjax() {
        return $this->presenter->isAjax();
    }

    public function flashMessage($message, $type = 'info') {
        return $this->presenter->flashMessage($message, $type);
    }

    /**
     * @return \Nette\Application\UI\ITemplate
     */
    protected function createTemplate() {
        $template = parent::createTemplate();
        $name = $this->reflection->shortName;
        $dir = $this->presenter->context->parameters['appDir'];
        $paths = array();
        $paths[] = "$dir/templates/components/$name/default.latte";
        $paths[] = "$dir/templates/components/$name.latte";
        foreach ($paths as $path) {
            if (is_file($path)) {
                $template->setFile($path);
                return $template;
            }
        }
        $template->setFile($paths[0]);
        return $template;
    }


}