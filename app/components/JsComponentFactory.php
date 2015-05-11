<?php

namespace App\Components;


use Closure\RemoteCompiler;
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

    /** @var bool */
    private $productionMode;

    /**
     * @param string $wwwPath
     * @param string $productionMode
     * @param IRequest $request
     */
    public function __construct($wwwPath, $productionMode, IRequest $request) {
        $this->wwwPath = $wwwPath;
        $this->request = $request;
        $this->productionMode = $productionMode;
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
        $files->addFile('assets/js/sortable.js');
        $files->addFile('assets/js/main.js');

        $compiler = Compiler::createJsCompiler($files, $this->wwwPath . '/webtemp');

        if ($this->productionMode) {
            $compiler->addFilter(function ($code) {
                $remoteCompiler = new RemoteCompiler();
                $remoteCompiler->addScript($code);
                $remoteCompiler->setMode(RemoteCompiler::MODE_SIMPLE_OPTIMIZATIONS);
                $compiled = $remoteCompiler->compile()->getCompiledCode();
                return $compiled ? $compiled : $code;
            });
        }

        $control = new JavaScriptLoader($compiler, $this->request->getUrl()->scriptPath . 'webtemp');
        return $control;
    }
}