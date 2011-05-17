<?php

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
				$this->model->storeNewPrice($this->price, $this->label, $this->loc);
			break;
			
			case: "update-price":
				$this->model->updatePrice($model->getId(), $this->price, $this->label, $this->loc);
			break;
			
			case "delete-price":
				$this->model->deletePrice($this->id);
			break;
		}
	}

}

?>