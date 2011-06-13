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
require_once ROOT_PATH .  '/Model/PricirModelOption.php';
require_once ROOT_PATH .  '/Model/PricirModelLov.php';
require_once ROOT_PATH . "/View/PricirViewNotifiers.php";


/**
 * @property PricirModelItem $modelItem
 * @property PricirModelGroup $modelGroup
 * @property PricirModelOption $modelOption
 * @property PricirModelLov $modelLov
 */
class PricirControllerInsert extends PricirControllerApp{
	private $currentItemId;
	private $currentGroupId;
	
	public $modelItem;
	public $modelGroup;
	public $modelOption;
	
	public function __construct() {
		parent::__construct();
		
		$this->modelGroup = new PricirModelGroup();
		$this->modelItem = new PricirModelItem();
	}
	
	public function storeNewItem($name, $thumbUrl, $basePrice, $groupId = NULL) {
		if (!isset($groupId)) 
			$groupId = $this->currentGroupId;
		
		$item = new PricirModelItem($name, $thumbUrl, $basePrice, $groupId);
		if ($item->insertNewItem()) 
			PricirViewNotifiers::staticTossNotif("New Item Stored", "info");
		
		$item_id = $this->currentItemId = $item->getItemId();
	}
	
	public function storeNewGroup($group_name) {
		if ($this->modelGroup->createNewGroup($group_name)) 
			PricirViewNotifiers::staticTossNotif(" New Group Stored", "info");
		
		$this->currentGroupId = $this->modelGroup->getGroupId();
	}
	
	public function storeNewOptionList($option_name, $item_id) {
		$modelLov = new PricirModelLov($option_name, $option_name);
		$modelLov->createLOV();
		
		$modelOption = new PricirModelOption();
		if ($modelOption->insertOption($option_name, $modelLov->getCurrentLovId()) ) {
			PricirViewNotifiers::staticTossNotif(" New Option List Stored", "info");
			$modelOption->associateItemOption($item_id);
			return $modelOption->getCurrentOptionId();
		} 
	}
	
	public function storeNewOptionDtl($operator, $price_change, $name, $lov_id) {
		$modelLov = new PricirModelLov();
		if ( $modelLov->createListValue($operator, $price_change, $name, $lov_id) )
			PricirViewNotifiers::staticTossNotif(" New Option Stored", "info");
	}
	
	public function setCurrentGroupId($group_id) {
		$this->currentGroupId = $group_id;
	}
	
	public function setCurrentItemId($item_id) {
		$this->currentItemId = $item_id;
	}
}

?>
