<?php

namespace gtf;

/**
 * Gtf Page Template - The abstract class. 
 * A simple template class aims to be fast and flexible.
 *
 * @author yfwz100
 * @version 0.6
 */
abstract class AbstractPage {

    private $page;    # the page block element
    private $tags;    # the tag stack
    private $templ;   # the extends template name
    private $var;     # the user-defined variables

    public function __construct() {
        $this->page = array();
        $this->tags = array();
        $this->var = array();
    }

    /**
     * Define the name with the value.
     * @param $name the name.
     * @param $value the value.
     */
    public function def($name, $value) {
        $this->var[$name] = $value;
    }

    /**
     * Create a block with a specific name.
     * The content of the block is defined by the first time. The other will be replaced by the first definition. Blocks can be nested.
     * @param $name the name of the block.
     */
    protected function block($name) {
        $this->tags[] = $name;
        ob_start();
    }

    /**
     * End the definition of a block.
     */
    protected function end() {
        $name = array_pop($this->tags);
        if (!array_key_exists($name, $this->page)) {
            $this->page[$name] = ob_get_clean();
        } else {
            ob_end_clean();
        }
        if ($this->templ == null) {
            echo $this->page[$name];
        }
    }

    /**
     * Extend a template.
     * @param $template the extended template.
     */
    protected function ext($template) {
        $this->templ = $template;
    }

    /**
     * Output the template with this page object.
     * @param $file the template file path relative to view folder.
     * @param $var the optional parameter which indicates variables used in the sub template.
     */
    public function template($file, array $var = null) {
        if (!isset($var)) {
            $var = $this->var;
        }

        ob_start();
        $this->template_impl($file, $var);
        
        if ($this->templ != null) {
            ob_end_clean();

            $templ = $this->templ;
            $this->templ = null;
            $this->template($templ);
        } else {
            ob_end_flush();
        }
    }

    /**
     * Include a template from the other view.
     * Note that the variables will be shared or you can specify the $var.
     * @param $template the template name.
     * @param $var the optional parameter which indicates variables used in the sub template.
     */
    protected function inc($template, array $var = null) {
        if (!isset($var)) {
            $var = $this->var;
        }

        $page = new Page;
        $page->var = $var;
        $page->page = $this->page;
        $page->template(dirname(dirname(dirname(__FILE__))).'/view/'.$template);
    }

    /**
     * The function used to find and render the template.
     * @param $file the file to be used as template.
     * @param $var the variables used in the template.
     */
    protected abstract function template_impl($file, array $var);
}

/**
 * The complete implement of the Gtf Page.
 */
class Page extends AbstractPage {

    protected function template_impl($file, array $var) {
        include $file;
    }

}

