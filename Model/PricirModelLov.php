<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirModelLov
 *
 * @author Jordan
 */

require_once ROOT_PATH . '/Model/PricirModelApp.php';
require_once ROOT_PATH . "/View/PricirViewNotifiers.php";

class PricirModelLov extends PricirModelApp {
	
	private $listName;
	private $listItemValue;
	private $listItemDisplay;
	private $currentLovId;
	
	public function __construct($listName = "", $listItemValue = "", $listItemDisplay = "") {
		parent::__construct();
		
		$this->listName = (is_string($listName) && isset($listName)) ? $listName : NULL;
		$this->listItemValue = (is_string($listItemValue) && isset($listItemValue)) ? $listItemValue : NULL;
		if (!isset($listItemDisplay)) {
			$this->listItemDisplay = $this->listItemValue;
		} else if (isset($listItemDisplay) && is_string($listItemDisplay)) {
			$this->listItemDisplay = $listItemDisplay;
		} else {
			$this->listItemDisplay = NULL;
		}
	}
	
	public function createLOV($name = "" ) {
		global $wpdb;
		$table = $this->prefix . "lov";
		
		if ( !isset($name) && $this->verifyDataMembers() ) 
			$name = $this->listName;
			
		$data = array("name" => $name);
		if ( $wpdb->insert($table, $data, array('%s')) ) { 
			$this->currentLovId = $wpdb->insert_id;
			return true;
		} else {
			return false;
		}
	}
	
	public function createListValue($operator, $price_change, $name, $lov_id = NULL) {
		global $wpdb;
		$table = $this->prefix . "lov_dtl";
		
		if (!isset($lov_id) && isset($this->currentLovId) && self::checkLovId($this->currentLovId))
			$_lov_id = $this->currentLovId;
		else if (isset($lov_id) && !isset($this->currentLovId) && self::checkLovId($lov_id)) 
			$_lov_id = $lov_id;
		else if (!isset($lov_id) && !isset($this->currentLovId)) {
			PricirViewNotifiers::staticTossNotif( "Could not insert List value. Please specify a lov_id", "info", 701);
			return false;
		}
		
		if ($this->verifyDtlElements($operator, $price_change, $name))
			$data = array("lov_id" => $_lov_id, "operator" => $operator, "price_change" => $price_change, "value" => $name, "display" => $name);
		else {
			PricirViewNotifiers::staticTossNotif( "Could not insert List value. Invalid Data Members", "info", 702);
			return false;
		}
		
		if ( $wpdb->insert($table, $data, array('%d', '%s', ' %s')) ) 
			return true;
		else 
			return false;	
	}
	
	public function updateLOV($lov_id, $newName) {
		global $wpdb;
		$data = array();
		$table = $this->prefix . "lov";
		
		// Make sure the new name is a valid bit of data
		$data['name'] = (is_string($newName) && strlen($newName) > 0) ? $newName : NULL;
		
		// Make sure this is a valid lov_id
		if (self::checkLovId($lov_id)) {
			$where = array("lov_id" => $lov_id);
			
			// try to update the lov
			if  ($wpdb->update($table, $data, $where, array('%s'), array('%d')) ) {
				$this->currentLovId = $lov_id;
				return true;
			} else {
				echo "Could not update list name, invalid data";
				return false;
			}
		}
	}
	
	public function updateLovDtl($lov_id_dtl, $lov_id, $newValue = "", $newDisplay = "") {
		global $wpdb;
		$data = array();
		$table = $this->prefix . "lov_dtl";
		
		$data['lov_id'] = (isset($lov_id) && is_int($lov_id) && self::checkLovId($lov_id)) ? $lov_id : NULL;
		$data['value'] = (isset($newValue) && is_str($newValue)) ? $newValue : NULL;
		if (isset($newDisplay) && is_string($newDisplay)) {
			$data['display'] = $newDisplay;
		} else if (!isset($newDisplay)) {
			$data['value'];
		}
		
		// Get rid of the nulls
		array_filter($data);
		
		$where = array('lov_dtl_id' => $lov_id_dtl);
		if ( $wpdb->update($table, $data, $where, array('%d', '%s', '%s'), array('%d')) ) {
			return true;
		} else {
			echo "Could not update list dtl";
			return false;
		}
	}
	
	public  function deleteLOV($lov_id) {
		global $wpdb;
		$table = $this->prefix . "lov_dtl";
		
		// First we check to see if this lov has any values referencing it in lov_dtl, if so, we cannot delete yet.
		$sql = "SELECT COUNT (lov_dtl_id) FROM $table WHERE lov_id = $lov_id";
		if ( $wpdb->query($wpdb->prepare($sql)) !== 0 ) {
			echo "Could not delete lov, lov_dtl still exists for lov_id = $lov_id";
			return false;
		} else {
			$table = $this->prefix . "lov";
			$sql = "DELETE FROM $table WHERE lov_id = $lov_id";
			
			if ( $wpdb->query($wpdb->prepare($sql)) !== false ) 
				return true;
			else {
				echo "Could not delete lov";
				return false;
			}	
		}
		
	}
	
	public function deleteLovDtl($lov_id_dtl, $lov_id) {
		global $wpdb;
		$table = $this->prefix . "lov_dtl";
		
		if (self::checkLovId($lov_id)) {
			$sql = "DELETE FROM $table WHERE lov_dtl_id = $lov_id_dtl";
			if ($wpdb->query($wpdb->prepare($sql)) !== false) 
				return true;
			else {
				echo "Could not delete lov_dtl = $lov_id_dtl";
				return false;
			}
		} else {
			echo "could not delete lov_dtl, LOV = $lov_id is invalid";
			return false;
		}
	}
	
	public function retrieveLovName($lov_id) {
		global $wpdb;
		$table = $this->prefix . "lov";
		
		$sql = "SELECT name FROM $table WHERE lov_id = $lov_id";
		 return $wpdb->get_var($wpdb->prepare($sql));
	}
	
	public function retrieveLovDtl($lov_dtl_id) {
		global $wpdb;
		$table = $this->prefix . "lov_dtl";
		
		$sql = "SELECT * FROM $table WHERE lov_dtl_id = $lov_dtl_id";
		return $wpdb->get_row($wpdb->prepare($sql), "ARRAY_A" );
	}
	
	public function retrieveLovDtlSet($start = NULL, $end = NULL) {
		global $wpdb;
		$table = $this->prefix . "lov_dtl";
		
		$sql = "SELECT * FROM $table WHERE lov_dtl_id >= $start AND lov_dtl_id <= $end";
		return $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
		
	}
	
	public function getCurrentLovId() {
		return $this->currentLovId;
	}

//	/////////////////////
//	STATIC
//	/////////////////////
	
	public static function createListValueStatic($data = array()) {
		$dataSchema = array("lov_id", "value", "display");
		$table = TABLE_NAME . "lov_dtl";
		$sortedData = array();
		global $wpdb;
		
		// Check the data to make sure it all exists and are strings. Then sort and rebuild the array.
		$sortedData["lov_id"] = (isset($data["lov_id"]) && is_string($data["lov_id"])) ? $data["lov_id"] : NULL;
		$sortedData["value"] = (isset($data["value"]) && is_string($data["value"])) ? $data["value"] : NULL;
		$sortedData["display"] = (isset($data["display"]) && is_string($data["display"])) ? $data["display"] : NULL;
		
		// Check the sorted data to make sure the values aren't null. If so, throw up an error.
		foreach ($dataSchema as $value) {
			if (!isset($sortedData[$value])) {
				echo "Could not create list value. $value not in data array";
				return false;
			}
		}
		
		// Check if there is a list associated with this id, if so insert the list item.
		if (self::checkLovId($sortedData["lov_id"])) {
			$wpdb->insert($table, $sortedData);
			return true;
		}
	}
	
	public static function checkLovId($lov_id) {
		global $wpdb;
		$bln = false;
		
		$sql = "SELECT * FROM " . $his->prefix . "lov WHERE lov_id = $lov_id";
		$result = $wpdb->query($wpdb->prepare($sql));
		
		if ($result > 0 && $result != NULL && $result !== false) {
			$bln = true;
		}
		
		return $bln;
	}
	
//	/////////////////////
//	PRIVATE
//	/////////////////////	
	
	private function verifyDataMembers() {
		$data = array($this->listName, $this->listItemValue);
		$bln = true;
		
		foreach ($data as $value) {
			if ($value === NULL)
				$bln = false;
		}
		
		return $bln;
	}
	
	private function verifyDtlElements($operator, $price_change, $name) {
		$data = array("operator" => $operator, "price_change" => $price_change, "name" =>$name);
		$bln = true;
		
		foreach ($data as $k => $v) {
			if ( !isset($v) || empty($v) ) 
				$bln = false;
			else if ( ($k == "operator" || $key == "name") && !is_string($v)  )
				$bln = false;
			else if ( $k == "price_change" && (!is_float($v) || !is_int($v)) )
				$bln = false;
		}
		
		return $bln;
	}
}

?>
