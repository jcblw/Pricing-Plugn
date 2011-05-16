<?php

require("../View/PricirViewForm.php") or die("Cannot find PricirViewForm.php in your the View directory.");
require("../Model/PricirModelForm.php") or die("Cannot find PricirModelForm.php in your the Model directory.");
require("../config.php") or die("Could not find the Config file in the root directory.");

class PricirControllerForm extends PricirControllerApp {
	private $action = $_POST['action'];
	private $price = $_POST['price'];
	private $loc = $_POST['location'];
	private $label = $_POST['label'];
	private $item = $_POST['item'];
		
	// this needs to be fixed
	// $model->getId() no longer exists
	public function determineAction($action = ACTION_FILE) {
		switch($action) {
			case "create-price" :
				$model->storeNewPrice($this->price, $this->label, $this->loc);
			break;
			
			case: "update-price":
				$model->updatePrice($model->getId(), $this->price, $this->label, $this->loc);
			break;
			
			case "delete-price":
				$model->deletePrice($this->id);
			break;
		}
	}

}

?>