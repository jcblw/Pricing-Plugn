<?php 

require_once("PricirViewApp.php");

class PricirForms extends PricirViewApp {
	
	public function displayCreateForm($action, $locations, $items) {
		
		// if has priviledges
		if(is_admin()) { ?>
			
			<div class="create-price">
				<form method="post" action="<?php echo $action; ?>">
					<ul>
						<li><label for="items">An Item for this price: </label><select name="items">
						<?php
							foreach ($items as $key => $value) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
							}
						?>
						</select></li>
						<li><label for="price">Price:</label><input type="text" class="price" name="price" id="price" value="" /></li>
						<li><label for="label">Description:</label><input type="text" class="label" name="label" id="label" value="" /></li>
						<li><label for="location">A Location for this price: </label><select name="location">
						<?php
							foreach ($locations as $key => $value) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
							}
						?>
						</select></li>
						
					</ul>
					
					<input type="hidden" name="action" value="create-price" />
				</form>
			</div>
			
		<?php } // endif
	} // end function

	// Move this function to the PricirPricesView class
	public function createAllPricesList($allPrices, $limit) {
		
		echo "<table>";
		echo "<tr>";
		
		$theads = "<th>Item</th>";
		$theads .= "<th>Price</th>";
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
		}
		
		echo "</table>"
	}
	
};



?>