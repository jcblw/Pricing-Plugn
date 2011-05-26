<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirControllerItemDisplay
 *
 * @author Jordan
 */

/**
 * @property PricirViewItem $itemView
 * @property PricirModelItem $itemModel
 */

require_once 'PricirControllerApp.php';
require_once ROOT_PATH . '/Model/PricirModelitem.php';
require_once ROOT_PATH . '/View/PricirViewItem.php';

class PricirControllerItemDisplay extends PricirControllerApp {
	private $itemView;
	private $itemModel;
	private $queryOffset;
	private $queryLimiter;
	private $itemAmt;
	private $linkBuffer;
	
	public function __construct($increment = 10, $linkBuffer = 3) {
		
		parent::__construct();
		
		$this->itemModel = new PricirModelItem(); 
		
		$this->linkBuffer = $linkBuffer;
		$this->itemAmt = $this->itemModel->getTotalItemCount();
		
		$this->itemView = new PricirViewItem($this->itemAmt, $increment, $linkBuffer);
		
		$this->queryOffset = $this->itemView->pagination->getCurrentOffset();
		
		if ($this->queryOffset == 1) {
			$this->queryLimiter = $this->queryOffset + $increment - 1;

		} else {
			$this->queryLimiter = $this->queryOffset + $increment;
		}
	}
	
	public function DisplayAllItemsList($newLimiter = NULL, $linkBuffer = NULL) {
		
		if (isset($newLimiter)) {
			$this->queryLimiter = $newLimiter;
		} else if (isset($linkBuffer)) {
			$this->linkBuffer = $linkBuffer;
		}
		
		$itemArray = $this->itemModel->retrieveItems($this->queryOffset, $this->queryLimiter);
		
		$this->itemView->createAllItemTable($itemArray);
	}
}

?>
