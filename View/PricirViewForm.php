<?php 

require_once("/PricirViewApp.php");

class PricirViewForm extends PricirViewApp {

	public function displayCreatePriceForm($action, $locations, $items) {
		
		// if has priviledges
		if(is_admin()) { ?>
			
			<div id="create-price" class="pricir">
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
					<input type='submit' value='create' />
				</form>
			</div>
			
		<?php } // endif
	} // end function
	
	// Needs more work after the whole iteming system has been more well thought out
	public function displayCreateItemForm($action) {
		echo "<div id='create-item' class='pricir'>";
			echo "<form action=' " . $action . " ' >";
				echo "<input type='text' name='item-name' />";
				echo "<input type='submit' value='create' />";
			echo "</form>";
		echo "</div>";
	}
	
	// fix pagination
	public function createAllPricesBatchEdit($allPrices, $limit, $offset, $action) {
	
		$i = $offset;
	
		$actions = array("delete" => "delete-price", "edit" => "inline-update-price");
		echo "<div id='edit-prices' class='pricir'>";
		echo "<form action =' " . $action . " ' method='post'>";
		echo "<table>";
		echo "<tr>";
		
		$theads = "<th>Edit?</th>";
		$theads .= "<th>ID</th>";
		$theads .= "<th>Price</th>";
		$theads .= "<th>Item</th>";
		$theads .= "<th>Description</th>";
		$theads .= "<th>Location</th>";
		$theads .= "<th>Action</th>";
		
		echo $theads;
		echo "</tr>";
		
		// This is going to need a lot more work because of validation
		foreach ($allPrices as $priceElement => $elementArray) {
			echo "<tr>";
				// The checkbox only makes the select statment active
				echo "<td><input type='checkbox' name='$priceElement' id=' " . $priceElement . "-CB' />";
				
				// eNames are 'price', 'item', 'location', and 'label'
				foreach ($elementArray as $eName => $eValue) {
					echo "<td><p>" . $eValue . "</p></td>"; 
				}	
				
				echo "<select name='action' id=' " . $priceElement ."-S'>";
				foreach ($actions as $key => $value) {
					echo "<option value=' " . $value . " ' > " . $key . " </option> ";
				}
				echo "</select>";
			echo "</tr>";
			
			if ( $i == $limit) { break; } else { $i++; }
		}
		
		echo "<tr><input type='submit' value='save' /></tr>";
		
		echo "</table>";
		echo "</form>";
		echo "</div>";
	}
	
}

?>