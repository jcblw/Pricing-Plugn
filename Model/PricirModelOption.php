<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirModelOption
 *
 * @author Jordan
 */

require_once ROOT_PATH . '/Model/PricirModelApp.php';
require_once ROOT_PATH . "/View/PricirViewNotifiers.php";

class PricirModelOption extends PricirModelApp {
	
	private $name;
	private $operator; 
	private $priceIncr;
	private $lovId;
	private $currentOptionId;


	public function __construct($name = "", $lovId = NULL, $insert = false) {
		parent::__construct();
		
		$this->name = (is_string($name) && strlen($name) > 0) ? $name : NULL;
		
		if (isset($lovId)) {
			$this->lovId = (self::checkLovId($lovId)) ? $lovId : NULL;
		}
		
		if ($insert == true) {
			$this->insertOption();
		}
		
	}
	
	public function insertOption($name = "", $lov_id = NULL) {
		global $wpdb;
		$data = array();
		
		$table = $this->prefix . "option";
		
		if ($this->verifyDataMembers() == true && (isset($lov_id) && !empty($name))) {
			$data['name' ] = $name;
			$data['lov_id'] = $lov_id;
		} else if ($this->verifyDataMembers() == true && (!isset($lov_id) && empty($name))) {
			$data['name'] = $this->name;
			$data['lov_id'] = $this->lovId;
		} else if ($this->verifyDataMembers() == false && (isset($lov_id) && !empty($name))) {
			$data['name' ] = $name;
			$data['lov_id'] = $lov_id;
		}  else {
			PricirViewNotifiers::staticTossNotif("Option data not valid. Could not Insert", "info");
			return false;
		}
			
		// Insert the data, and return true if successful, else false
		if( $wpdb->insert($table, $data, array("%s", "%d")) ) {
			$this->currentOptionId = $wpdb->insert_id;
			return true;
		} else {
			return false;
		}
	}
	
	public function associateItemOption ($item_id, $option_id = NULL) {
		global $wpdb;
		$data = array();
		$table = TABLE_NAME . "item_option";
		
		$data['item_id'] = $item_id;
		$data['option_id'] = (isset($option_id)) ? $option_id : $this->currentOptionId;
		
		if ($wpdb->insert($table, $data, array('%d', '$d')));
	}
	
	public function updateOption($option_id, $newData = array()) {
		$dataSchema = array("name", "price_incr", "operator", "lov_id");
		$oldData = $this->retrieveOption($option_id);
		$table = TABLE_NAME . "option";
		global $wpdb;
		
		if (isset($newData)) {
			// Let's check if our new data array, includes all the values in our table. If it doesn't let's replace it with
			// values that are currently in our table.
			foreach ($dataSchema as $value) {
				if (!isset($newData[$value])) 
					$newData[$value] = $oldData[$value];
			}

			// Now let's unset any bits of the array that aren't in our data schema
			$newDataKeys = array_keys($newData);

			foreach ($newDataKeys as $value) {
				if (!isset($dataSchema[$value]))
					unset($newData[$value]);
			}
			
			// Update our record
			$whereArray = array("option_id" =>$option_id);
			$wpdb->update($table, $newData, $whereArray, array('%s', '%f', '%s', '%d'), array('%s'));
			
		} else if (!isset($newData)) {
			if (!$this->verifyDataMembers()) {
				echo "Option data not  valid. Cannot Update";
				return false;
			} else {
				$newData = array("name" => $this->name, "price_incr" => $this->priceIncr, "operator" => $this->operator, "lov_id" => $this->lovId);
				$whereArray = array("option_id" =>$option_id);
				$wpdb->update($table, $newData, $whereArray, array('%s', '%f', '%s', '%d'), array('%s'));
			}	
		}
	}	
	
	public function retrieveOption($option_id) {
		global $wpdb;
		
		$sql = "SELECT * FROM " . TABLE_NAME . "option WHERE option_id = $option_id";
		$data = $wpdb->get_row($wpdb->escape($sql), 'ARRAY_A');
		
		return $data;
	}
	
	public function deleteOption($option_id) {
		global $wpdb;
		
		$sql = "DELETE FROM " . $this->prefix . "option WHERE option_id = $option_id";
		$wpdb->query($wpdb->escape($sql));
	}
	
	public function getCurrentOptionId() {
		return $this->currentOptionId;
	}
	
//	/////////////////////
//	STATIC
//	/////////////////////
	
	public static function InsertOptionStatic($name, $operator, $priceIncr, $lovId) {
		$data = array("name" => $name, "price_incr" => $priceIncr, "operator" => $operator, "lov_id" => $lovId);
		
		// First, let's make sure all of our data is accurate
		if (isset($data) && is_array($data)) {
			$data['name'] = (is_string($data['name']) && strlen($data['name']) > 0) ? $data['name'] : NULL;
			$data['operator'] = (is_string($data['operator']) && strlen($data['operator']) > 0 && strlen($data['operator']) <= 2) ? $data['operator'] : NULL;
			$data['price_incr'] = (is_int($data['price_incr']) && $data['price_incr'] > 0) ? $data['price_incr'] : NULL;
			$data['lov_id'] = (self::checkLovId($data['lov_id'])) ? $data['lov_id'] : NULL;
		} 
		
		foreach ($data as $key => $value)  {
			if ($value === NULL) {
				echo "Option data not valid. Cannot Insert";
				return false;
			}
		}
		
		$sql = "SELECT * FROM " . TABLE_NAME . "lov_dtl WHERE value = $name AND lov_id = $lovId" ;
		if ($wpdb->query($wpdb->escape($sql), "ARRAY_A") !== false) {
			$table = TABLE_NAME . "option";
			
			// Insert the data, and return true if successful, else false
			if( $wpdb->insert($table, $data, array("%s", "%f", "%s", "%d")) ) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public static function checkLovId($lov_id) {
		global $wpdb;
		$bln = false;
		
		$sql = "SELECT * FROM " . $his->prefix . "lov WHERE lov_id = $lov_id";
		$result = $wpdb->query($wpdb->escape($sql));
		
		if ($result > 0 && $result != NULL && $result !== false) {
			$bln = true;
		}
		
		return $bln;
	}
	
	public static function getOptionCount() {
		global $wpdb;
		
		$sql = "SELECT COUNT (option_id) FROM " . TABLE_NAME . "option ";
		return (int)$wpdb->get_var($wpdb->escape($sql));
	}
	
//	/////////////////////
//	PRIVATE
//	/////////////////////
	
	private function verifyDataMembers() {
		$data = array ('name"' => $this->name, "operator" => $this->operator, "price_incr" => $this->priceIncr, "lov_id" => $this->lovId);
		$bln = false;
		
		foreach ($data as $key => $value) {
			$bln = ($value === NULL) ? false : true;
		} 
		
		return $bln;
	}
}

?>