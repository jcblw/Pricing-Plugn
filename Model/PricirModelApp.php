<?php

class PricirModelApp {

	protected $currentVer;
	protected $prefix;
	
	public function __construct() {
		$this->currentVer = CURRENT_VERSION;
		$this->prefix = PREFIX;
	}
	
	public function setInstalledVer($ver) {
		$this->InstalledVer = $ver;
	}
	
	public function getInstalledVer() {
		return $this->InstalledVer;
	}
	
	public function getCurrentVer() {
		return $this->currentVer;
	}
	
	public function getAllPrices($filter = false) {
		global $wpdb;
		$arr = array();
	
		if ($filter) {
			$sql = "SELECT price, item, label, location FROM ";
			$sql .= $this->tableName;
			$sql .= " WHERE " . $filter;
			
			$arr = $wpdb->get_results($sql, ARRAY_A);
		} else {
			$sql = "SELECT price, item, label, location FROM ";
			$sql .= $this->tableName;
			
			$arr = $wpdb->get_results($sql, ARRAY_A);
		}
		
		return $arr;
	}
	
}

?>