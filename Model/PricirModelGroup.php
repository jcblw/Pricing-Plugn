<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirModelGroup
 *
 * @author Jordan
 */

require_once ROOT_PATH . '/Model/PricirModelApp.php';

class PricirModelGroup extends PricirModelApp {
	private $group_id;
	
	public function createNewGroup($GroupName) {
		global $wpdb;
		
		if (is_string($GroupName)) {
			$data = array('name' => $GroupName);
			$table = TABLE_NAME."group";
			
		 	if ($wpdb->insert($table, $data)) {
				$this->group_id = $wpdb->insert_id;
				return true;
			}
		}
	}
	
	public function associateGroupItem($item_id, $group_id) {
		global $wpdb;
		
		if (is_int($group_id) && is_int($item_id)) {
			$table = TABLE_NAME."item_group";
			$data = array("item_id" => $item_id, "group_id" => $group_id);
			
			if ($wpdb->insert($table, $data, array('%d', '%d'))) {
				$wpdb->insert($table, $data);
				$this->group_id = $wpdb->insert_id;
				return true;
			}
			
		} else {
			PricirViewNotifiers::staticTossNotif("Could Not Associate Group and Item", "alert", 301);
		}
	}
	
	public function retrieveGroupName($id) {
		global $wpdb;
		
		if (is_int($id)) {
			$table = TABLE_NAME."group";
			$sql = "SELECT name FROM $table WHERE id = $id";
			
			return $wpdb->get_var($sql);
		}
	}
	
	public function retrieveGroupArray($offset = NULL, $limit = NULL, $output_type = "ARRAY_A") {
		global $wpdb;
		
		$table = TABLE_NAME . "group";
		$sql = "SELECT * FROM $table";
		
		if ($limit != 0 && $offset != 0 && $offset != 1) {
			$sql .= " WHERE group_id >= $offset AND group_id  < $limit"; 
		} elseif (($limit != 0 && $offset != 0) && ($offset == 1)) {
			$sql .= " WHERE group_id >= $offset AND group_id <= $limit";
		}
		
		$sql .= " ORDER BY group_id";
		
		if ($output_type != "ARRAY_A" && $output_type != "ARRAY_N") {
			$output_type = "ARRAY_A";
		}
		
		$results = $wpdb->get_results($sql, $output_type);
		foreach ($results as &$result)
			settype($result['group_id'], "integer");
		if (!is_array($results)) {
			PricirViewNotifiers::staticTossNotif("Could not retrieve group array", "info", 302);
			return false;
		} else {
			return $results;
		}
	}
	
	public function getGroupId() {
		return $this->group_id;
	}
}

?>