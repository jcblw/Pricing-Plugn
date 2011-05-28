<?php
global $wpdb;

// Path to the Root, used for includes
define("ROOT_PATH", __DIR__);

// path to the action file
define("ACTION_FILE", "action.php");

// current version of the plugin
define("CURRENT_VERSION", 1.0);

// name of the table in the database
define("TABLE_NAME", "{$wpdb->prefix}pricir_");

?>