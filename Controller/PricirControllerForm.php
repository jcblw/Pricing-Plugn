<?php

require("../View/PricirViewForm.php") or die("Cannot find PricirViewForm.php in your the View directory.");
require("../Model/PricirModelForm.php") or die("Cannot find PricirModelForm.php in your the Model directory.");
require("../config.php") or die("Could not find the Config file in the root directory.");

class PricirControllerForm extends PricirControllerApp {
	private $action = $_POST['action'];
	private $price = $_POST['price'];
	private $loc = $_POST['location'];
	private $label = $_POST['label'];
	private $item = $_POST['item'];
	private $id = $_POST['id'];
	
	// This needs to be moved to the parent class
	public function pricirInitialize() {
		global $wpdb;
		$installedVer = $model->getInstalledVer();
		
		if ($installed_ver === 0) {
			$tableName = $wpdb->prefix . "pricir_data";
			$sql = "CREATE TABLE " . $tableName . " ( ";
			$sql .= "id mediumint(9) NOT NULL AUTO_INCREMENT,";
			$sql .= "price float(6, 2) NOT_NULL,";
			$sql .= "item varchar(25) NOT_NULL,";
			$sql .= "label varchar(60) NOT_NULL";
			$sql .= "location varchar(15) NOT_NULL,";
			$sql .= ");";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			dbDelta($sql) or die("could not create table");
			
			add_option("pricir_db_version", $this->currentVer);
			$model->setInstalledVer(CURRENT_VERSION);
		}
	}
	
	// this needs to be fixed
	// $model->getId() no longer exists
	public function determineAction($action = ACTION_FILE) {
		switch($action) {
			case "create-price" :
				$model->storeNewPrice($this->price, $this->label, $this->loc);
			break;
			
			case: "update-price":
				$model->updatePrice($model->getId(), $this->price, $this->label, $this->loc);
			break;
			
			case "delete-price":
				$model->deletePrice($this->id);
			break;
		}
	}

}

?>