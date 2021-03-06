<?php

namespace App\Components;


use Nette\Http\IRequest;
use Nette\Object;
use Nette\Utils\Strings;
use WebLoader\Compiler;
use WebLoader\FileCollection;
use WebLoader\InvalidArgumentException;
use WebLoader\Nette\CssLoader;

/**
 * Factory for generating css component
 * @package Minicup\Components
 */
class CssComponentFactory extends Object {
    /** @var IRequest */
    public $request;
    /** @var  string */
    private $wwwPath;
    /** @var  bool */
    private $productionMode;

    /**
     * @param $wwwPath
     * @param $productionMode
     * @param IRequest $request
     */
    public function __construct($wwwPath, $productionMode, IRequest $request) {
        $this->wwwPath = $wwwPath;
        $this->productionMode = $productionMode;
        $this->request = $request;
    }

    /**
     * @return CssComponentFactory|CssLoader
     * @throws InvalidArgumentException
     */
    public function create() {
        $files = new FileCollection($this->wwwPath);
        $control = $this;
        $files->addFile('assets/less/style.css');

        $compiler = Compiler::createCssCompiler($files, $this->wwwPath . '/webtemp');

        $compiler->addFileFilter(function ($code, Compiler $loader, $file = null) use ($control) {
            return Strings::replace($code, "#\.\./#", $control->request->url->scriptPath . "assets/");
        });

        if ($this->productionMode) {
            $compiler->addFilter(function ($code) {
                return \CssMin::minify($code);
            });
        }
        $control = new CssLoader($compiler, $this->request->url->scriptPath . 'webtemp');
        return $control;
    }
}