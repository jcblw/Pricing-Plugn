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

include("/Controller/PricirControllerForm.php");
include("/Controller/PricirControllerInsert.php");
include("/Controller/PricirControllerItemDisplay.php");

register_activation_hook(__FILE__, "pricir_plugin_init");
add_action("admin_menu", "pricir_admin_init");



function pricir_admin_init() {
	add_menu_page(	'Pricir',
					'Pricir',
					'manage_options',
					'pricir-menu-parent',
					'pricirAdminOutput' );
	
	add_submenu_page( 'pricir-menu-parent', 'Manage items', 'Manage Items', 'manage_options', 'pricir-manage-items', 'pricirManageitems');
}

function pricirAdminOutput() {
	$itemCon = new PricirControllerItemDisplay(2, 2);
	$itemCon->DisplayAllItemsList();
}

function pricirManageItems() {
	$itemCon = new PricirControllerItemDisplay(5, 1);
	$itemCon->DisplayAllItemsList();
}

function pricir_plugin_init() {
	$con = new PricirControllerApp();
	$con->modelDBS->dbInit();
}


?>