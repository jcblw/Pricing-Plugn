<?php

if(isset($_POST['action'])) {
	
	switch($_POST['action']) {
		case "insert-item":
			$insertCon = new PricirControllerInsert();
			
			$name = $_POST['item-name'];
			$url = $_POST['item-url'];
			$price = (float) $_POST['item-price'];
			if (empty($_POST['new-item-group'])) {
				$group = (int)  $_POST['item-group'] ;
				$insertCon->storeNewItem($name, $url, $price, $group);
			} else {
				$new_group_name = (string) $_POST['new-item-group'];
				$new_group_id = $insertCon->storeNewGroup($new_group_name);
				
				$insertCon->storeNewItem($name, $url, $price, $new_group_id);	
			}
		break;
		
		case "insert-option":
			$insertCon = new PricirControllerInsert();
			
			$option_name = $_POST['option-lov-name'];
			$item_id = $_POST['option-item'];
			
			$option_id = $insertCon->storeNewOptionList($option_name, $item_id);
			wp_redirect(MANAGE_OPTIONS_URL . "&option=$option_id");
		break;
	
		case "insert-option-dtl":
			$insertCon = new PricirControllerInsert();
			
			$option_id = $_POST['option-id'];
			$operator = $_POST['option-dtl-operator'];
			$price_change = $_POST['option-dtl-name'];
			$price_change = $_POST['option-dtl-price-change'];
			
			$insertCon->storeNewOptionDtl($operator, $price_change, $name, $lov_id);
		break;
	}
	
}


?>