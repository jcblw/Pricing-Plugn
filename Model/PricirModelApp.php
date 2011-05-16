<?php

require("../config.php") or die("Could not find the Config file in the root directory.");

class PricirModelApp {

	global const CURRENT_VERSION;
	global const TABLE_NAME;
	
	global $wpdb;
	protected $installedVer;
	
	public function setInstalledVer($ver) {
		$this->InstalledVer = $ver;
	}
	
	public function getInstalledVer() {
		return $this->InstalledVer;
	}
	
	public function getCurrentVer() {
		return self::CURRENT_VERSION;
	}
	
	public function getAllPrices($filter = false) {
		$tableName = self::TABLE_NAME;
		$arr = array();
	
		if ($filter) {
			$sql = "SELECT price, item, label, location FROM ";
			$sql .= $tableName;
			$sql .= " WHERE " . $filter;
			
			$arr = $this->wpdb->get_results($sql, ARRAY_A);
		} else {
			$sql = "SELECT price, item, label, location FROM ";
			$sql .= $tableName;
			
			$arr = $this->wpdb->get_results($sql, ARRAY_A);
		}
		
		return $arr;
	}
	
}

?>