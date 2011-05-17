<?php

require_once("PricirModelApp.php") or die("Could not find PricirModelApp.php in the /Model directory");

class PricirModelForm extends PricirModelApp {

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
		
		$this->wpdb->query($sql) or die("Could not delete from database");
	}
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
}

?>