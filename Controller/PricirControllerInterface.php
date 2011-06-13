<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This class handles the display of forms and ensures that all of the forms are properly named.
 *
 * @author Jordan
 */

require_once ROOT_PATH . "/Controller/PricirControllerApp.php";
require_once ROOT_PATH . "/Model/PricirModelItem.php";
require_once ROOT_PATH . "/Model/PricirModelGroup.php";
require_once ROOT_PATH . "/Model/PricirModelOption.php";
require_once ROOT_PATH . "/View/PricirViewForm.php";

/**
 * @property PricirModelItem $modelItem
 * @property PricirModelGroup $modelGroup
 * @property PricirModelOption $modelOption
 * @property PricirViewForm $viewForm
*/

class PricirControllerInterface extends PricirControllerApp {
	
	public $modelItem;
	public $modelGroup;
	public $modelOption;
	public $viewForm;
	
	public function __construct() {
		parent::__construct();
		
		$this->modelItem = new PricirModelItem();
		$this->modelGroup = new PricirModelGroup();
		$this->modelOption = new PricirModelOption();
		$this->viewForm = new PricirViewForm();
	}
	
	public function createItemInsertInterface() {
		$groupArray = $this->modelGroup->retrieveGroupArray();
		$this->viewForm->createInsertNewItemForm($groupArray);
	}
	
	public function createOptionInsertInterface() {
		$item_array = $this->modelItem->retrieveItems(0, 0, "ARRAY_A", true);
		$group_ref_array = $this->modelGroup->retrieveGroupArray();
			
		$this->viewForm->createInsertNewOptionForm($item_array, $group_ref_array);
	}
	
	public function createOptionInsertDtlInterface($option_id) {
		$this->viewForm->createInsertOptionDtlForm($option_id);
	}
}

?>
