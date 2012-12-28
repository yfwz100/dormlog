<?php
namespace comp;

class CalendarTable {

	private $cur;
	private $header;
	private $action;

	public static function instance(array $action=null, DateTime $date=null) {
		$cal = new CalendarTable;
		if (isset($action)) {
			$cal->__set('action', $action);
		}
		return $cal;
	}

	public function __construct($cur = null) {
		if (isset($cur)) {
			$this->cur = $cur;
		} else {
			$this->cur = new \DateTime();
		}
		$this->header = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$this->action = array();
		$this->action['today'] = '';
		$this->action['day'] = '';
		$this->action['table_class'] = '';
	}

	public function __set($attr, $value) {
		switch($attr) {
			case 'action':
				$keys = array_keys($this->action);
				foreach($keys as $key) {
					if (array_key_exists($key, $value)) {
						$this->action[$key] = $value[$key];
					}
				}
				break;
			case 'header':
				if (count($value) == 7) {
					$this->header = $value;
				}
				break;
		}
	}

	public function __get($attr) {
		return $this->$attr;
	}

	public function __toString() {
		$year = $this->cur->format('Y');
		$month = $this->cur->format('m');

		$start = new \DateTime("$year-$month-1");
		$end = new \DateTime("$year-$month-1");
		$end->modify('+1 month');
		$end->modify('-1 day');

		$sweekday = (int) $start->format('N');
		$days = (int) $end->format('j');
		$today = (int) $this->cur->format('j');

		$html = "<table class='calendartable {$this->action['table_class']}'>";
		$html .= "<caption>$year</caption>";
		$html .= "<thead><tr>";
		foreach ($this->header as $key=>$value) {
			$html .= "<th>$value</th>";
		}
		$html .= "</tr></thead>";

		$html .= "<tbody><tr>";
		for ($i=1; $i<$sweekday; $i++) {
			$html .= "<td></td>";
		}
		for ($i=1; $i<=$days; $i++) {
			if ($i == $today) {
				if ($this->action['today'] != null) {
					$path = strtr($this->action['today'], array('%d'=>$i, '%m'=>$month, '%Y'=>$year));
					$html .= "<td class='selected'><a href='$path'>$i</a></td>";
				} else {
					$html .= "<td class='selected'>$i</td>";
				}
			} else {
				if ($this->action['day'] != null) {
					$path = strtr($this->action['day'], array('%d'=>$i, '%m'=>$month, '%Y'=>$year));
					$html .= "<td><a href='$path'>$i</a></td>";
				} else {
					$html .= "<td>$i</td>";
				}
			}
			if (($remain=($i + $sweekday-1) % 7) == 0) {
				$html .= "</tr><tr>";
			}
		}
		$remain = 7 - $remain;
		while ($remain--) {
			$html .= "<td></td>";
		}
		$html .= "</tr></tbody>";

		$html .= "</table>";

		return $html;
	}
}
