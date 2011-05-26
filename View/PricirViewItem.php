<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricirViewItems
 *
 * @author Jordan
 */

require_once 'PricirViewPagination.php';
require_once 'PricirVIewApp.php';

/**
 * @property PricirViewPagination $pagination
*/

class PricirViewItem extends PricirViewApp {
	
	private $itemArray;
	public $pagination;
	
	public function __construct($totalItemAmt, $increment = 10, $linkBuffer = 3) {
		$this->pagination = new PricirViewPagination($totalItemAmt, $increment, $linkBuffer);
		$this->itemArray = $itemArray;
	}
	
	public function createAllItemTable($itemArray) {
		$i = $this->pagination->getCurrentOffset();
		$lmit = $this->pagination->getIncrement() + $i;
		
		echo "<table>";
		echo "<tr>";
		
		$theads = "<th>ID</th>";
		$theads .= "<th>Item</th>";
		$theads .= "<th>Thumbnail</th>";
		$theads .= "<th>Price</th>";
		
		echo $theads;
		echo "</tr>";
		
		foreach ($itemArray as $priceElement => $elementArray) {
			echo "<tr>";
			
				// eNames are 'price', 'item', 'location', and 'label'
				foreach ($elementArray as $eName => $eValue) {
					echo "<td>" . $eValue . "</td>"; 
				}	
				
			echo "</tr>";
			
			if ( $i == $limit) { break; } else { $i++; }
		}
		
		echo "</table>";
		
		$this->pagination->createNav();
	}
}

?>
