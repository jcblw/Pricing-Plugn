<?php

require_once("PricirModelApp.php") or die("Could not find PricirModelApp.php in the /Model directory");

class PricirModelPrices extends PricirModelApp {

	public function getAllPrices($filter = false) {
		$tableName = self::TABLE_NAME;
		$arr = array();
	
		if ($filter) {
			$sql = "SELECT id, price, item, label, location FROM ";
			$sql .= $tableName;
			$sql .= " WHERE " . $filter;
			
			$arr = $this->wpdb->get_results($sql, ARRAY_A);
		} else {
			$sql = "SELECT id, price, item, label, location FROM ";
			$sql .= $tableName;
			
			$arr = $this->wpdb->get_results($sql, ARRAY_A);
		}
		
		return $arr;
	}
	
	public function getSinglePriceArray($id, $format, $offset = 0) {
		$arr = array();
		$formats = array('OBJECT', 'ARRAY_A', 'ARRAY_N');
		
		$tableName = self::TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		$sql = "SELECT price, item, label, location FROM ";
		$sql .= $tableName . $where;
		
		if(!in_array($format, $formats)) { 
			die("not a valid format");
			return false;
		}
		
		$arr = $this->wpdb->get_row($sql, $format, $offset);
		return $arr;
	}
	
}

?>