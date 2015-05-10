<?php

namespace App\Components;


use Nette\Http\IRequest;
use Nette\Object;
use WebLoader\Compiler;
use WebLoader\FileCollection;
use WebLoader\InvalidArgumentException;
use WebLoader\Nette\JavaScriptLoader;

/**
 * Factory for generating js component
 * @package Minicup\Components
 */
class JsComponentFactory extends Object {
    /** @var string */
    private $wwwPath;

    /** @var IRequest */
    private $request;

    /**
     * @param string $wwwPath
     * @param string $productionMode
     * @param IRequest $request
     */
    public function __construct($wwwPath, IRequest $request) {
        $this->wwwPath = $wwwPath;
        $this->request = $request;
    }

    /**
     * @param string $module
     * @return JavaScriptLoader
     * @throws InvalidArgumentException
     */
    public function create() {
        $files = new FileCollection($this->wwwPath);
        $files->addFile('assets/js/jquery.js');
        $files->addFile('assets/js/nette.ajax.js');
        $files->addFile('assets/js/nette.forms.js');
        $files->addFile('assets/js/bootstrap.js');
        $files->addFile('assets/js/toastr.js');
        $files->addFile('assets/js/main.js');

        $compiler = Compiler::createJsCompiler($files, $this->wwwPath . '/webtemp');

        $control = new JavaScriptLoader($compiler, $this->request->getUrl()->scriptPath . 'webtemp');
        return $control;
    }
}