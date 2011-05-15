<?php

require_once("../Controller/PricirControllerForm.php") or die("Cound not find PricirControllerForm.php in the controller directory.");
$controller = new PricirControllerForm;

$controller->determineAction();

?>