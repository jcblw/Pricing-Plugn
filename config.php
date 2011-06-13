<?php

// Path to the Root, used for includes
define("ROOT_PATH", ABSPATH . "wp-content/plugins/pricing-plugn");

// path to the action file
define("ACTION_FILE", ROOT_PATH . "/action.php");

// current version of the plugin
define("CURRENT_VERSION", 1.0);

// name of the tables in the database
define("TABLE_NAME", "{$wpdb->prefix}pricir_");

// url to the manage items page
define("MANAGE_ITEMS_URL", admin_url("/admin.php?page=pricir-manage-items"));

// url to the manage options page
define("MANAGE_OPTIONS_URL", admin_url("/admin.php?page=pricir-manage-options"));

// url to the settings page
define("SETTINGS_URL", admin_url("/admin.php?page=pricir-settings"));

?>