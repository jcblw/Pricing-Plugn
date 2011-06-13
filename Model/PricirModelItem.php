<?php 

require_once ROOT_PATH . "/Model/PricirModelApp.php";
require_once ROOT_PATH . "/View/PricirViewNotifiers.php";

class PricirModelItem extends PricirModelApp {

	private $name;
	private $thumbUrl;
	private $basePrice;
	private $group_id;
	
	private $id;
	
	public function __construct($name = "", $thumbUrl = "", $basePrice = NULL, $group_id = NULL) {
                (!empty($name) && is_string($name)) ? $this->name = $name : $this->name = NULL;
                (isset($basePrice) && is_float($basePrice)) ? $this->basePrice = $basePrice : $this->basePrice = NULL;
		(isset($group_id) && is_int($group_id)) ? $this->group_id = $group_id : $this->group_id = NULL;
				
		if (!empty($thumbUrl) && is_string($thumbUrl)) {
			if ($this->checkImageURL($thumbUrl)) {
				$thumbUrl = $this->convertURL($thumbUrl);
			}
			$this->thumbUrl = $thumbUrl;
		} else {
			$this->thumbUrl = NULL;
		}
	}
        
	public function getTotalItemCount() {
		global $wpdb;
		
		$table = TABLE_NAME . "item";
		$sql = $wpdb->prepare("SELECT COUNT(item_id) FROM $table;");
		return (int)$wpdb->get_var($sql);
	}
	
	public function retrieveItems($offset = 0, $limit =0, $output_type = "ARRAY_A", $inc_group_id = false) {
		global $wpdb;
		
		$table = TABLE_NAME . "item";
		$sql = "SELECT * FROM $table";
		
		if ($limit != 0 && $offset != 0 && $offset != 1) {
			$sql .= " WHERE item_id > $offset AND item_id  <= $limit"; 
		} elseif (($limit != 0 && $offset != 0) && ($offset == 1)) {
			$sql .= " WHERE item_id >= $offset AND item_id <= $limit";
		}
		
		if ($output_type != "ARRAY_A" || $output_type != "ARRAY_N") {
			$output_type = "ARRAY_A";
		}
		
		$results = $wpdb->get_results($sql, $output_type);
		
		$table = TABLE_NAME . "group_item";
		$sql = "SELECT group_id FROM $table";
		
		if ($limit != 0 && $offset != 0 && $offset != 1) {
			$sql .= " WHERE item_id > $offset AND item_id  <= $limit"; 
		} elseif (($limit != 0 && $offset != 0) && ($offset == 1)) {
			$sql .= " WHERE item_id >= $offset AND item_id <= $limit";
		}
	
		$group_ids = $wpdb->get_col($sql, 0);

		$table = TABLE_NAME . "group";
		foreach ($results as $key => &$result) {
			$sql = "SELECT name FROM $table WHERE group_id = $group_ids[$key]";
			$result['group_name'] = $wpdb->get_var($sql);
			if ($inc_group_id)
				$result['group_id'] = (int) $group_ids[$key];
		}
		
		if (!is_array($results)) {
			PricirViewNotifiers::staticTossNotif("Could not retrieve item array", "info", 201);
			return false;
		} else {
			return $results;
		}
	}
	
	public function retrieveSingleItem($item_id, $output_type = "ARRAY_A") {
		global $wpdb;
		
		$table = TABLE_NAME . "item";
		$sql = "SELECT * FROM $table WHERE item_id = $item_id";
		
		if ($output_type != "ARRAY_A" || $output_type != "ARRAY_N") {
			$output_type = "ARRAY_A";
		}
		
		if ($wpdb->get_row($sql, $output_type)) {
			$item = $wpdb->get_row($sql, $output_type);
			
			$table = TABLE_NAME . "group_item";
			$sql = "SELECT group_id FROM $table WHERE item_id = $item[item_id]";

			$group_id = $wpdb->get_var($sql);
			
			$table = TABLE_NAME . "group";
			$sql = "SELECT name FROM $table WHERE group_id = $group_id";
			
			$group_name = $wpdb->get_var($sql);
			$item['group_name'] = $group_name;
			return $item;
		} else {
			PricirViewNotifiers::staticTossNotif("Could not retrieve item array", "info", 201);
			return false;
		}
	}
	
	public function deleteItemById($item_id) {
		global $wpdb;
		
		$table = TABLE_NAME . "group_item";
		$sql - "DELETE FROM $table WHERE item_id = $item_id";
		$wpdb->query($sql);
		
		$table = TABLE_NAME . "item";
		$sql = "DELETE FROM $table WHERE item_id = $item_id";
		$wpdb->query($sql);
	}
	
	public function updateItemById($id, $updated_name = "", $updated_thumb_url = "", $updated_base_price = 0, $updated_group_id = 0) {
		global $wpdb;
		$table = TABLE_NAME . "item";
		
		if (!isset($newItem)) {
			$updated_data = array("name" => $updated_name, "thumb_url" =>$updated_thumb_url, "base_price" => $updated_base_price);
			array_filter($updated_data);
		} 
		
		$where = array("id" => $id);
		if ($this->DB->update($table, $updated_data, $where)) {
			unset($updated_data);
			$table = TABLE_NAME . "group_item";
			
			$updated_data = array("group_id" => $updated_group_id);
			$where = array("item_id" => $id);
			
			$wpdb->update($table, $updated_data, $where);
		} else {
			PricirViewNotifiers::staticTossNotif("Could not update item", "info", 202);
			return false;
		}
	}

	public function getItemId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function insertNewItem() {
		global $wpdb;
		$table = TABLE_NAME . "item";
		
		if ($this->verifyDataMembers(false)) {
			 $name = $this->name;
			 $basePrice = $this ->basePrice;
			 $thumbUrl = $this->thumbUrl;
			 $group_id = $this->group_id;
		} else {
			PricirViewNotifiers::staticTossNotif("Could not insert item. Data members not valid", "info", 203);
			return false;
		}
		
		$data = array("name" => $name, "thumb_url" => $thumbUrl, "base_price" => $basePrice);
		array_filter($data);	
		
		$wpdb->insert($table, $data);
		$id = $this->id = $wpdb->insert_id;				
		
		if (isset($group_id)) {
			$table = TABLE_NAME . "group_item";
			$item_group = array("item_id" => $id, "group_id" => $group_id);
			$wpdb->insert($table, $item_group, array('%d', '%d'));
			
			return true;
		}
		
		return true;
	}
	
	public static function insertNewItemStatic(PricirModelItem $item = NULL) {
		global $wpdb;
		$table = TABLE_NAME . "item";
			
		if ($item !== NULL) {
			if ($item->verifyDataMembers(false)) {
				$name = $item->name;
				$basePrice = $item ->basePrice;
				$thumbUrl = $item->thumbUrl;
				$group_id = $item->group_id;

			} else {
				$name = $basePrice = $thumbUrl = $group_id = NULL;
			}
		} else { 
			return false;
		}
		 
		$data = array("name" => $name, "thumb_url" => $thumbUrl, "base_price" => $basePrice);
		array_filter($data);	
		
		if (!empty($data)) {
			
			$wpdb->insert($table, $data, array('%s', '%s', '%f'));
			$id = $wpdb->insert_id;
			$item->setId($id);				
				
			if (isset($group_id)) {
				$table = TABLE_NAME . "group_item";
				$item_group = array("item_id" => $id, "group_id" => $group_id);
				$wpdb->insert($table, $item_group, array('%d', '%d'));
					
				return true;
			}
				
			return true;
		} else {
			PricirViewNotifiers::staticTossNotif("Could Not Insert Item", "alert", 204);
		}
		 
	}
	
//	/////////////////////
//	PRIVATE
//	/////////////////////
	private function convertURL($url) {
		return htmlentities(urlencode($url));
	}
		
	private function checkImageURL($url) {
		$exts = array("jpg", "jpeg", "gif", "png");
		
		$ext = substr(strpos($url, "."), 1);
		if (in_array($ext, $exts)) {
			return true;
		} else {
			return false;
		}
	}
		
	private function verifyDataMembers($checkGroup = true) {
		$group_id = $this->group_id;
		$base_price = $this->basePrice;
		$thumb_url = $this->thumbUrl;
		$name = $this->name;
		
		$bln = true;
		
		if (  ( !empty($base_price) && !empty($thumb_url) && !empty($name) ) 
				&& ( (is_float($base_price) || is_int($base_price) ) && (is_string($thumb_url)) && is_string($name) )  ) {
			$bln = true;
			
			if ($checkGroup) {
				if (empty($group_id) || !is_int($group_id)) {
					$bln = false;
				}
			}
		} else {
			$bln = false;
		}
		return $bln;
	}
	
}

 ?>