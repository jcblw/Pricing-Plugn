<?php
/*
Plugin Name: Pricir
Plugin URI: https://github.com/jacoblwe20/pricing-plugn
Description: A plugin used to create item-price associations and calculate the prices associated with varying attributes of the item
Version: 1
Author: Jordan Lovato
License: GPL2
.
The most awesome plugin ever created
.
*/
require_once __DIR__ . "/config.php";
require_once ROOT_PATH . "/includes/ControllerIncludes.php";

register_activation_hook(__FILE__, "pricir_plugin_init");
add_action("admin_menu", "pricir_admin_init");

function pricir_admin_init() {
	add_menu_page( 'Pricir', 'Pricir', 'manage_options', 'pricir-menu-parent', 'pricirAdminOutput' );
	add_submenu_page( 'pricir-menu-parent', 'Manage items', 'Manage Items', 'manage_options', 'pricir-manage-items', 'pricirManageitems');
}

function pricirAdminOutput() {
	$itemCon = new PricirControllerItemDisplay(2, 2);
	$itemCon->DisplayAllItemsList();
	
//	$insertCon = new PricirControllerInsert;
//	$insertCon->storeNewGroup("Clothes");
//	for ($i = 0; $i < 20; $i++) {
//		$insertCon->storeNewItem("pants", 20.00, "notAJpg.jpg", 1);
//	}
}

function pricirManageItems() {
	$itemCon = new PricirControllerItemDisplay(5, 1);
	$itemCon->DisplayAllItemsList();
}

function pricir_plugin_init() {
	$con = new PricirControllerApp();
	$con->modelDBS->dbInit();
	
	$insertCon = new PricirControllerInsert;
	if ($insertCon->modelItem->getTotalItemCount() == 0) {
		$insertCon->storeNewGroup("Clothes");
		
		for ($i = 0; $i < 20; $i++) {
			$insertCon->storeNewItem("pants", 20.00, "notAJpg.jpg", 1);
		}
	}
}


?>