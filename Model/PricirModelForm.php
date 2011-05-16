<?php

require_once("PricirModelApp.php");
require("../config.php") or die("Could not find the Config file in the root directory.");

class PricirModelForm extends PricirModelApp {
	global $wpdb;
	global const CURRENT_VERSION;
	global const TABLE_NAME;
	
	public __construct() {
		if (get_option("pricir_db_version")) {
			$this->installedVer = get_option("pricir_db_version");
		} else {
			$this->installedVer = 0;
		}
	}

	public function storeNewPrice($price, $item, $label, $loc) {
		$data = array('price' => $price, 'item' => $item, 'label' => $label, 'location' => $loc);
	
		$this->wpdb->insert($tableName, $data, array('%f', '%s', '%s')) or die("Could not insert data into table");
	}

	public function updatePrice($id, $price, $item ="", $label = "", $loc = "") {
		$data = array('price' => $price);
		$tableName = self::TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		if (strlen($label) > 0) { $data['label'] = $label; } 
		if (strlen($loc) >  0) { $data['loc'] = $loc; }
		if (strlen($item) > 0) { $data['item'] = $item; }
		
		$this->wpdb->update($tableName, $data, $where, array('%f', '%s', '%s', '%s'), '%s') or die('Could not update database');
	}

	public function deletePrice($id) {
		$tableName = self::TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		$sql = "DELETE FROM ";
		$sql .= $tableName;
		$sql .= $where;
		
		$this->wpdb->query($sql) or die("Could not delte from database");
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
	
	public function getPriceArray($id, $format, $offset = 0) {
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
	}
	
	public function getInstalledVer() {
		return $this->InstalledVer;
	}
	
	public function getCurrentVer() {
		return self::CURRENT_VERSION;
	}
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function setInstalledVer($ver) {
		$this->InstalledVer = $ver;
	}
	
};

?>