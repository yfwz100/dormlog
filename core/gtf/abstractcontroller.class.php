<?php
namespace gtf;

abstract class AbstractController {

	protected $page;

	public function __construct() {
		$this->page = new Page();
	}

    public function __call($method, $params) {
        return $this->error('No such page.');
    }

	protected function done($message='Success!', $link=null) {
        header("Location: " . $link);

        $this->page->def('link', $link);
        $this->page->def('message', $message);
        $this->page->template(dirname(dirname(dirname(__FILE__))).'/view/done.phtml');
    }

    protected function error($message = 'Unknown error!', $link=null) {
        if (! isset($link) && isset($_SERVER['HTTP_REFERER'])) {
            $this->page->def('link', $_SERVER['HTTP_REFERER']);
        } else {
            $this->page->def('link', $link);
        }
        $this->page->def('message', $message);
        $this->page->template(dirname(dirname(dirname(__FILE__))).'/view/error.phtml');
    }
}
