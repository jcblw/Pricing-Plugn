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
			
			// Create our group table. This table is used to organize items into several groups. These groups
			// are what we would call "Pricirs".
			$sql = "CREATE TABLE " . $this->prefix . "group (
			group_id mediumint(10) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			PRIMARY KEY  (group_id),
			UNIQUE KEY (name)
			) ENGINE=INNODB;\n";
			
			// Create our item table, This funcitons as storage, for the name, id, thumbnail, and price for each
			// item in create by Pricir. Whenever we create an item, it is associated to a group, and a record is
			// created in both this table and the Item_group table
			$sql .= "CREATE TABLE " . $this->prefix . "item (
			item_id mediumint(10) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			base_price float(10,2) NOT NULL,
			thumb_url varchar(255) NOT NULL,
			PRIMARY KEY  (item_id),
			UNIQUE KEY (name)
			) ENGINE=INNODB;\n";
			
			// Create our group_item table, This table will serve as an access table for our items and our 
			// gorups. It restricts what can be deleted from either table (eg. if there is an item_group record
			// but we try to delete that group or id, we cannot).
			$sql .= "CREATE TABLE " . $this->prefix . "group_item (
			group_id mediumint(10) NOT NULL,
			item_id mediumint(10) NOT NULL,
			PRIMARY KEY  (group_id, item_id),
			FOREIGN KEY (group_id)
			REFERENCES " . $this->prefix . "group (group_id)
			ON DELETE RESTRICT,
			FOREIGN KEY (item_id)
			REFERENCES " . $this->prefix . "item (item_id)
			ON DELETE RESTRICT
			) ENGINE=INNODB;\n";
			
			// Create the table lov. This table is used to organize the lists of values (lov) for our options. So if 
			// we want to create a new option, we would first populate lov with a new list name and id, then 
			// populate lov_dtl with values for that lov, then reference the lov_id from this table, and the value
			// that we want from lov_dtl. So for each option associated with an item, we have 1 lov_dtl value.
			$sql .= "CREATE TABLE " . $this->prefix . "lov (
			lov_id mediumint(10) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			PRIMARY KEY  (lov_id),
			UNIQUE KEY (name)
			) ENGINE=INNODB;\n";
			
			// Create the table lov_dtl. This table stores values for a single lov_id. Then an option can 
			// be created using one of these values and the proper lov_id.
			$sql .= "CREATE TABLE " . $this->prefix . "lov_dtl (
			lov_dtl_id mediumint(10) NOT NULL AUTO_INCREMENT,
			lov_id mediumint(10) NOT NULL,
			operator varchar(15) NOT NULL,
			price_change float(10, 2) NOT NULL,
			value varchar(255) NOT NULL,
			display varchar(255) NOT NULL,
			PRIMARY KEY  (lov_dtl_id),
			UNIQUE KEY (value, lov_id),
			FOREIGN KEY (lov_id)
			REFERENCES " . $this->prefix . "lov (lov_id)
			ON DELETE RESTRICT
			) ENGINE=INNODB;\n";
			
			// Create the options table. This table hands all options associated with an item. it is also 
			// given a value from our lov table to associate it with a group. 
			$sql .= "CREATE TABLE " . $this->prefix . "option (
			option_id mediumint(10) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			lov_id mediumint(10) NOT NULL,
			PRIMARY KEY  (option_id),
			UNIQUE KEY (name, lov_id),
			FOREIGN KEY (name, lov_id)
			REFERENCES " . $this->prefix . "lov_dtl (value, lov_id)
			ON DELETE RESTRICT
			) ENGINE=INNODB;\n";
			
			// Create the table item_option, to help us manage options and associate them 
			// with different items. Also restrict what can be deleted from this table, based on what
			// is in the items table and the options table.
			$sql .= "CREATE TABLE " . $this->prefix . "item_option (
			item_id mediumint(10) NOT NULL,
			option_id mediumint(10) NOT NULL,
			PRIMARY KEY  (item_id, option_id),
			FOREIGN KEY (item_id)
			REFERENCES " . $this->prefix . "item (item_id)
			ON DELETE RESTRICT,
			FOREIGN KEY (option_id)
			REFERENCES " . $this->prefix . "option (option_id)
			ON DELETE RESTRICT
			) ENGINE=INNODB;\n";

		
			// Include the neccessary files and run the sql.
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			global $wpdb;
			
			$sql = "CREATE FUNCTION f_valueInLOV(in_lov_id mediumint(10), in_value varchar(255))
				 RETURNS varchar(1) DETERMINISTIC 
				 BEGIN
					DECLARE isFound varchar(1);
					SELECT IF (COUNT(*) = 0,'N','Y')
					INTO isFound
					FROM " . $this->prefix . "lov_dtl d
					WHERE d.lov_id = in_lov_id
					AND d.value = in_value;
					RETURN isFound;
				  END;";
			
			$wpdb->query($wpdb->escape($sql));
			
			$sql = "CREATE TRIGGER " . $this->prefix . "option_bi BEFORE INSERT ON " . $this->prefix . "option
				  FOR EACH ROW
				  BEGIN
					IF f_valueInLOV(NEW.lov_id, NEW.name) = 'N' THEN
						SET NEW.name = NULL;
					END IF;
				 END;";
			
			$wpdb->query($wpdb->escape($sql));
			
			update_option("pricir_db_version", $this->currentVer);
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