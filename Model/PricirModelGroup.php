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
		
		if (is_string($GroupName) && strlen($GroupName) <= 35) {
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
			die("Could Not Associate Group and Item.");
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
	
	public function getGroupId() {
		return $this->group_id;
	}
}

?>