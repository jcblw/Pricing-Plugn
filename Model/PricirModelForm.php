<?php

require_once("PricirModelApp.php");
require("../config.php") or die("Could not find the Config file in the root directory.");

class PricirModelForm extends PricirModelApp {
	global $wpdb;
	global const CURRENT_VERSION;
	global const TABLE_NAME;
	
	private $tableName = TABLE_NAME;
	private $currentVer = CURRENT_VERSION;
	private $installedVer;
	
	public __construct() {
		if (get_option("pricir_db_version")) {
			$this->installedVer = get_option("pricir_db_version");
		} else {
			$this->installedVer = 0;
		}
	}

	public function storeNewPrice($id, $price, $label, $loc) {
		global $wpdb;
		$wpdb->insert($tableName, array('price' => $price, 'label' => $label, 'location' => $loc), array('%f', '%s', '%s')) or die("could not insert data into table");
	}
	// Needs to be rewritten
	public function updatePrice($id, $price, $label = "", $loc = "") {

	}
	// Check for delete
	public function deletePrice($id) {
		if (get_option("Price-" . $id)) {
			delete_option("Price-" . $id);
			delete_option("Label-". $id);
			delete_option("Location-" . $id);
		} else {
			die("Invalid call to Price-$id");
		}
	}
	// Check for delete
	public function getPriceArray($id) {
		if (get_option("Price-" . $id)) {
			$arr = array();
		
			$arr["Price-" . $id] = get_option("Price-" . $id);
			$arr["Label-". $id] = get_option("Label-". $id);
			$arr["Location-" . $id] = get_option("Location-" . $id);
			
			return $arr;
		} else {
			return false;
		}
	}
	
	public function getInstalledVer() {
		return $this->InstalledVer;
	}
	
	public function setInstalledVer($ver) {
		$this->InstalledVer = $ver;
	}
	
};

?>