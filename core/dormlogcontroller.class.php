<?php

use gtf\Page, gtf\AbstractController, gtf\PDOBox;

class DormLogController extends AbstractController {

	private $length;

    public function __construct() {
        parent::__construct();
        $this->length = 10;
    }

    public function year($year=array()) {
    	if (! empty($year)) {
        	$cond = " where strftime('%Y',`date`)=:year";
        	$params = array(':year'=>$year[0]);
        } else {
        	$cond = '';
        	$params = array();
        	$d = new DateTime;
        	$year[] = $d->format('Y');
        }
    	$this->index($year[0], $cond, $params);
    }

    public function date($date=array()) {
    	if (! empty($date)) {
    		$d = new DateTime($date[0]);
    		$year = $d->format('Y');

    		$cond = " where date(`date`)=:date ";
    		$params = array(':date'=>$date[0]);
    	}
    	$this->index($year, $cond, $params);
    }

    public function index($year=null, $cond=null, $params=null) {
    	if (! $year) {
    		$this->page->def('all', true);
    	} else {
    		$this->page->def('all', false);
    	}

    	$page = isset($_GET['page']) ? $_GET['page']-1 : 0;

    	if (! $cond) {
    		$cond = " limit {$page},{$this->length}";
    	} else {
    		$cond .= " limit {$page},{$this->length}";
    	}

    	try {
    		$dbh = PDOBox::get();

    		$sql = "select sum(`amount`),count(`amount`) from `dormlog` $cond";
	        $stat = $dbh->prepare($sql);
	        if (! $stat) {
	        	$this->error($sql);
	        	return;
	        }
	        $stat->execute($params);
	        $row = $stat->fetch();

	        $pages = $row[1]/$this->length;
	        if ($page < 0 || $page > $row) {
	        	$this->error("Are you sure you're right?");
	        	return;
	        }

	        $this->page->def('sum', $row[0]);
	        $this->page->def('pages', $pages);
	        $this->page->def('prev_page', $page-1);
	        $this->page->def('current_page', $page);
	        $this->page->def('next_page', $page+1);	

	        $sql = "select * from `dormlog` $cond";
	        $stat = $dbh->prepare($sql);
	        if (! $stat) {
	        	$this->error($sql);
	        	return;
	        }
	        $stat->execute($params);
	        $log = array();
	        while ($row=$stat->fetch()) {
	        	$log[] = $row;
	        }
	        $this->page->def('log', $log);

	        $sql = "select distinct strftime('%Y',`date`) year from `dormlog` order by `date` desc";
	        $stat = $dbh->prepare($sql);
	        $stat->execute();
	        $years = array();
	        while ($row = $stat->fetch()) {
	        	if ($year == $row[0]) {
	        		$years[$row[0]] = true;
	        	} else {
	        		$years[$row[0]] = false;
	        	}
	        }
	        $this->page->def('years', $years);

	        $this->page->template('view/index.phtml');
    	} catch(Exception $e) {
    		$this->error($e->getMessage());
    	}
        
    }

    public function addLog($params) {
    	$date = $_POST['date'];
    	$detail = $_POST['detail'];
    	$amount = $_POST['amount'];

    	try {
    		$dbh = PDOBox::get();
    		$stat = $dbh->prepare("insert into dormlog values(null, date(:date), :detail, :amount)");
    		$r = $stat->execute(array(':date'=>$date, ':detail'=>$detail, ':amount'=>$amount));
    		if ($r) {
    			$this->done('Successfully inserted the record...', $_SERVER['PHP_SELF']);
    			return;
    		} else {
    			$this->done('It seems that there\'s some problems...', $_SERVER['PHP_SELF']);
    			return;
    		}
    	} catch(Exception $e) {
    		$this->error($e->getMessage());
    	}
    }

}
