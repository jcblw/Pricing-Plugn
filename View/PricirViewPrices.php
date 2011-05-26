<?php

require_once("/PricirViewApp.php");

class PricirViewPrices extends PricirViewApp {

	// A Table strictly for display purposes, if you want an 
	// editable table use createAllPricesBatchEdit in the PricirViewForm class
	
	public function createAllPricesList($allPrices, $limit, $offset) {
		$i = $offset;
		
		echo "<table>";
		echo "<tr>";
		
		$theads = "<th>ID</th>";
		$theads .= "<th>Price</th>";
		$theads .= "<th>Item</th>";
		$theads .= "<th>Description</th>";
		$theads .= "<th>Location</th>";
		
		echo $theads;
		echo "</tr>";
		
		foreach ($allPrices as $priceElement => $elementArray) {
			echo "<tr>";
			
				// eNames are 'price', 'item', 'location', and 'label'
				foreach ($elementArray as $eName => $eValue) {
					echo "<td>" . $eValue . "</td>"; 
				}	
				
			echo "</tr>";
			
			if ( $i == $limit) { break; } else { $i++; }
		}
		
		echo "</table>";
	}
}

?>