<?php

require_once ROOT_PATH . "/Model/PricirModelApp.php";

class PricirModelForm extends PricirModelApp {

	public function __construct() {
		parent::__construct();
		
	}

	public function storeNewPrice($price, $item, $label, $loc) {
		$data = array('price' => $price, 'item' => $item, 'label' => $label, 'location' => $loc);
	
		$this->DB->insert($tableName, $data, array('%f', '%s', '%s')) or die("Could not insert data into table");
	}

	public function updatePrice($id, $price, $item ="", $label = "", $loc = "") {
		$data = array('price' => $price);
		$tableName = self::TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		if (strlen($label) > 0) { $data['label'] = $label; } 
		if (strlen($loc) >  0) { $data['loc'] = $loc; }
		if (strlen($item) > 0) { $data['item'] = $item; }
		
		$this->DB->update($tableName, $data, $where, array('%f', '%s', '%s', '%s'), '%s') or die('Could not update database');
	}

	public function deletePrice($id) {
		$tableName = self::TABLE_NAME;
		$where = " WHERE id = ' " . $id . " ' ";
		
		$sql = "DELETE FROM ";
		$sql .= $tableName;
		$sql .= $where;
		
		$this->DB->query($sql) or die("Could not delete from database");
	}
	
	public function getTableName() {
		return self::TABLE_NAME;
	}

}

?>