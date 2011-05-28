<?php 

require_once ROOT_PATH . '/Model/PricirModelApp.php';

// class needs to be modified for updates
class PricirModelDBStructure extends PricirModelApp {
	private $installed;
	private $updated;
	
	public function __construct() {
		parent::__construct();
		
		if (get_option("pricir_db_version") && get_option("pricir_db_version") ==  $this->currentVer) {
			$this->installed = true;
			$this->updated = true;
		} else if (get_option("pricir_db_version") && get_option("pricir_db_version") < $this->CurrentVer) {
			$this->installed = true;
			$this->updated = false;
		} else {
			$this->installed = false;
			$this->updated = false;
		}
	}

	// function needs to be modified for upgrading
	public function dbInit() {
		if ( $this->installed !== true || $this->updated !== true) {
		
			// Create our tables baby!
			$sql = "CREATE TABLE " . $this->prefix . "group ( 
						id int(10) NOT NULL AUTO_INCREMENT,
						name varchar(35) NOT NULL,
						 PRIMARY KEY  (id)
					); 
					CREATE TABLE " . $this->prefix . "item_group (
						id int(10) NOT NULL AUTO_INCREMENT,
						item_id int(10) NOT NULL,
						group_id int(10) NOT NULL,
						 PRIMARY KEY  (id)
					);
					CREATE TABLE " . $this->prefix . "item (
						id int(10) NOT NULL AUTO_INCREMENT,
						name varchar(35) NOT NULL,
						thumb_url varchar(100) NOT NULL,
						base_price float(10,2) NOT NULL,
						 PRIMARY KEY  (id)
					);
					CREATE TABLE " . $this->prefix . "option (
						id int(10) NOT NULL AUTO_INCREMENT,
						name varchar(35) NOT NULL,
						type varchar(35) NOT NULL,
						price_change float(10,2) NOT NULL,
						operator varchar(10) NOT NULL,
						 PRIMARY KEY  (id)
					);
					CREATE TABLE " . $this->prefix . "item_option (
						id int(10) NOT NULL AUTO_INCREMENT,
						item_id int(10) NOT NULL,
						option_id int(10) NOT NULL,
						 PRIMARY KEY  (id)
					); ";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			add_option("pricir_db_version", $this->currentVer);
			$this->installed = true;
			$this->updated = true;
		}
	}
	
	public function checkInstalled() {
		return $this->installed;
	}
	
	public function checkUpdated() {
		return $this->updated;
	}
}

 ?>