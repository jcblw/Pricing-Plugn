<?php

require_once("../View/PricirViewForm.php") or die("Could not find PricirViewForm.php in the /View directory.");
require_once("../Model/PricirModelForm.php") or die("Could not find PricirModelForm.php in the /Model directory.");

class PricirControllerApp {
	protected $view = new PricirViewForm;
	protected $model = new PricirModelForm;

	final public function dbInitialize() {
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

}

?>