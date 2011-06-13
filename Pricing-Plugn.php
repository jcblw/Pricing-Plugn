<?php
/*
Plugin Name: Pricir
Plugin URI: https://github.com/jacoblwe20/pricing-plugn
Description: A plugin used to create item-price associations and calculate the prices associated with varying attributes of the item
Version: 1.0
Author: Jordan Lovato
License: GPL2
.
The most awesome plugin ever created
.
*/
?>
<?php

require_once ABSPATH . "/wp-content/plugins/pricing-plugn/config.php";
require_once ROOT_PATH . "/includes/ControllerIncludes.php";

register_activation_hook(__FILE__, "pricir_plugin_init");
add_action("admin_menu", "pricir_admin_init");

function pricir_admin_init() {
	add_menu_page( 'Pricir Settings', 'Pricir', 'manage_options', 'pricir-settings', 'pricirSettings' );
	add_submenu_page( 'pricir-settings', 'Manage items', 'Manage Items', 'manage_options', 'pricir-manage-items', 'pricirManageitems');
	add_submenu_page( 'pricir-settings', 'Manage Options', 'Manage Options', 'manage_options', 'pricir-manage-options', 'pricirManageOptions');
}

function pricirSettings() {
	include_once ACTION_FILE;
	echo "<div class='wrap'>";

	echo "</div>";
}

function pricirManageItems() {
	include_once ACTION_FILE;
	echo "<div class='wrap'>";
	
	if (isset($_GET['action'])) {
		echo "<h2>Edit Item</h2>";
		$item_id = substr($_GET['action'], 5);
		$display = new PricirControllerItemDisplay(1,1);
		$display->displayItemEditTable($item_id);
	} else { 
		echo "<h2>Manage Items</h2>";
		$display = new PricirControllerItemDisplay(5, 1);
		$display->displayAllItemsList();
		$interface = new PricirControllerInterface();
		$interface->createItemInsertInterface();
	}
	echo "</div>";
}

function pricirManageOptions() {
	include_once ACTION_FILE;
	echo "<div class='wrap'>";
	
	if (isset($_GET['action']) && isset($_GET['option'])) {
		// html after the option is inserted
		$option_id = $_GET['option'];
		$interface = new PricirControllerInterface();
		$interface->createOptionInsertDtlInterface($option_id);
	} else { 
		// default html
		$interface = new PricirControllerInterface();
		$interface->createOptionInsertInterface();
	}
	echo "</div>";
}

function pricir_plugin_init() {
	$con = new PricirControllerApp();
	$con->modelDBS->dbInit();
	
	$insertCon = new PricirControllerInsert;
	if ($insertCon->modelItem->getTotalItemCount() == 0) {
		$insertCon->storeNewGroup("clothes");
		$insertCon->storeNewItem("pants", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("shirt", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("tie", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("socks", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("hat", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("jacket", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("belt", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("shoes", "notAJpg.jpg", 20.00, 1);
		$insertCon->storeNewItem("undershirt", "notAJpg.jpg", 20.00, 1);
	}
}


?>