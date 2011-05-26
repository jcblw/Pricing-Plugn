<?php

require_once( dirname(__DIR__) .  "/config.php" );

require_once( ROOT_PATH . "/Model/PricirModelPrices.php" );
require_once( ROOT_PATH . "/Model/PricirModelDBStructure.php" );

/**
 * @property PricirModelDBStructure $modelDBS
 * @property PricirModelPrices $modelPrices
 */

class PricirControllerApp {
	public $modelDBS;
	public $modelPrices;
		
	public function __construct() {
		$this->modelDBS = new PricirModelDBStructure;
		$this->modelPrices = new PricirModelPrices;
	}

}

?>