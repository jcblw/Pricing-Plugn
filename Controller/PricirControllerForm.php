<?php

require_once ROOT_PATH . "/Controller/PricirControllerApp.php";

require_once ROOT_PATH .  "/View/PricirViewForm.php" ;
require_once ROOT_PATH . "/Model/PricirModelForm.php";

require_once ROOT_PATH . "/View/PricirViewPagination.php";

/**
 * @param PricirViewForm $viewForm
 * @param PricirModelForm $modelForm
 */

class PricirControllerForm extends PricirControllerApp {
	private $action;
	private $price;
	private $loc;
	private $label;
	private $item;
	
	public $viewForm;
	public $modelForm;


	public function __construct() {
		parent::__construct();
		
		(isset($_POST['action'])) ? $this->action = $_POST['action'] : $this->action = null;
		(isset($_POST['price'])) ? $this->price = $_POST['price'] : $this->price = null;
		(isset($_POST['location'])) ? $this->loc = $_POST['location'] : $this->loc = null;
		(isset($_POST['label'])) ? $this->label = $_POST['label'] : $this->label = null;
		(isset($_POST['item'])) ? $this->item = $_POST['item'] : $this->item = null;
		
		$this->viewForm = new PricirViewForm;
		$this->modelForm = new PricirModelForm;
	}
	
	// this needs to be fixed
	// $model->getId() no longer exists
	/*
	public function determineAction($action = ACTION_FILE) {
		switch($action) {
			case "create-price" :
				$this->model->storeNewPrice($this->price, $this->label, $this->loc);
			break;
			
			case: "update-price":
				$this->model->updatePrice($model->getId(), $this->price, $this->label, $this->loc);
			break;
			
			case "delete-price":
				$this->model->deletePrice($this->id);
			break;
		}
	} */
	
	public function getPricesForm() {
		$totalAmt = $this->modelPintrices->getPricesCount();
		
		$pagination = new PricirViewPagination($totalAmt);
		$sqlInsert = "id  > " . $pagination->getCurrentOffset();
		
		$prices = $this->model->getAllPrices($sqlInsert);
		
		$pagination->createNav();
	}

}

?>