<?php

require_once ROOT_PATH . "/Model/PricirModelApp.php";

class PricirModelPrices extends PricirModelApp {

	public function __construct() {
		parent::__construct();
	}

	public function getAllPrices($filter = false) {
		$tableName = self::TABLE_NAME;
		$arr = array();
	
		if ($filter) {
			$sql = "SELECT id, price, item, label, location FROM ";
			$sql .= $tableName;
			$sql .= " WHERE " . $filter;
			
			$arr = $this->DB->get_results($sql, ARRAY_A);
		} else {
			$sql = "SELECT id, price, item, label, location FROM ";
			$sql .= $tableName;
			
			$arr = $this->DB->get_results($sql, ARRAY_A);
		}
		
		return $arr;
	}
	
	public function getSinglePriceArray($id, $format, $offset = 0) {
		$arr = array();
		$formats = array('OBJECT', 'ARRAY_A', 'ARRAY_N');
		
		$tableName = TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		$sql = "SELECT price, item, label, location FROM ";
		$sql .= $tableName . $where;
		
		if(!in_array($format, $formats)) { 
			die("not a valid format");
			return false;
		}
		
		$arr = $this->DB->get_row($sql, $format, $offset);
		return $arr;
	}
	
	public function getPricesCount() {
		$arr = array();
		$count = 0;
		
		$sql = "SELECT id FROM ";
		$sql .= TABLE_NAME;

		$arr = $this->DB->get_col($sql, 0);
		$count = count($arr);
		
		return $count;
	}
	
}

?>