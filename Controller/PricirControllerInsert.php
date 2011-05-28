<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirControllerInsert
 *
 * @author Jordan
 */
require_once ROOT_PATH . '/Controller/PricirControllerApp.php';
require_once ROOT_PATH .  '/Model/PricirModelGroup.php';
require_once ROOT_PATH .  '/Model/PricirModelItem.php';


/**
 * @property PricirModelItem $modelItem
 * @property PricirModelGroup $modelGroup
 */
class PricirControllerInsert extends PricirControllerApp{
	private $currentItemId;
	private $currentGroupId;
	
	private $modelItem;
	private $modelGroup;
	
	public function __construct() {
		parent::__construct();
		
		$this->modelGroup = new PricirModelGroup;
	}
	
	public function storeNewItem($name, $base_price, $thumbUrl = "", $group_id = NULL) {
		if (!isset($group_id)) {
			$group_id = $this->currentGroupId;
		}
		
		$item = new PricirModelItem($name, $thumbUrl, $base_price, $group_id);
		if ($item->insertNewItem()) {
			echo "Success, New Item Stored!";
		}
		
		$item_id = $this->currentItemId = $item->getItemId();
	}
	
	public function storeNewGroup($group_name) {
		if ($this->modelGroup->createNewGroup($group_name)) {
			echo "Succes, New Group Stored!";
		}
		
		$this->currentGroupId = $this->modelGroup->getGroupId();
	}
	
	public function setCurrentGroupId($group_id) {
		$this->currentGroupId = $group_id;
	}
	
	public function setCurrentItemId($item_id) {
		$this->currentItemId = $item_id;
	}
}

?>
