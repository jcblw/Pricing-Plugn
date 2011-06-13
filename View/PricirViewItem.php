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

// Includes PricirViewApp class
require_once ROOT_PATH . "/View/PricirViewPagination.php";

/**
 * @property PricirViewPagination $pagination
*/

class PricirViewItem extends PricirViewApp {
	
	public $pagination;
	
	public function __construct($totalItemAmt, $increment = 10, $linkBuffer = 3) {
		$this->pagination = new PricirViewPagination($totalItemAmt, $increment, $linkBuffer);
	}
	
	public function createAllItemTable($itemArray) {
		$i = $this->pagination->getCurrentOffset();
		$limit = $this->pagination->getIncrement() + $i;
		
		echo "<table class='widefat'>";
		
		echo "<thead>";
		echo "<tr>";
		
		$theads = "<th>ID</th>";
		$theads .= "<th>Item</th>";
		$theads .= "<th>Base Price</th>";
		$theads .= "<th>Image</th>";
		$theads .= "<th>Group</th>";
		$theads .= "<th>Edit</th>";
		
		echo $theads;
		echo "</tr>";
		echo "</thead>";
		
		echo "<tbody>";
		foreach ($itemArray as $priceElement => $elementArray) {
			echo "<tr>";
			
				// eNames are 'price', 'item', 'location', and 'label'
				foreach ($elementArray as $eName => $eValue) {
					if ($eName == "thumb_url") {
						echo "<td><img src='" . $eValue . "' class='pricir-thumbnail' /></td>";
					} else if ($eName == "base_price") {
						echo "<td>\$$eValue</td>";
					} else {
						echo "<td>" . $eValue . "</td>";
					}
				}	
				
				echo "<td><a href='?page=$_GET[page]&action=edit-$elementArray[item_id]' class='pricir-edit-item'>edit</a></td>";
				
			echo "</tr>";
			
			if ( $i == $limit) { break; } else { $i++; }
		}
		echo "</tbody>";
		
		echo "</table>";
		
		$this->pagination->createNav();
	}
	
	public function createEditItemTable($singleItemArray, $groupsArray) {
		echo "<form action='" . ACTION_FILE . "' method='POST' id='pricir-edit-item-form'>"; 
		
		echo "<table class='widefat'>";
		
		
		echo "<thead>";
		echo "<tr>";
		
		$theads = "<th>ID</th>";
		$theads .= "<th>Item</th>";
		$theads .= "<th>Base Price</th>";
		$theads .= "<th>Image</th>";
		$theads .= "<th>Group</th>";
		
		echo $theads;
		echo "</tr>";
		echo "</thead>";
		
		echo "<tbody>";
		echo "<tr>";

		foreach ($singleItemArray as $key => $value) {
			if ($key == "item_id") {
				echo "<td>$value</td>";
			} else if ($key == "group_name") {
			?>
			<td><select name="group_id">
			<?php	foreach ($groupsArray as $k => $singleGroupArray) { ?>
			<option value="<?php echo $singleGroupArray['group_id']; ?>" <?php if ($singleGroupArray['name'] == $singleItemArray['group_name']) { echo "selected='selected'"; } ?>><?php echo $singleGroupArray['name']; ?></option>
			<?php } ?>
			</select></td>
			<?php } else { ?>
			<td><input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" class="pricir-input-edit" /></td>
			<?php } }
		echo "</tr>";
		echo "</tbody>";
		
		echo "</table>";
		?>
		<input type="hidden" name="action" value="update-item" />
		<input type="submit" class="button-secondary" name="submit" value="save" />
		<?php
		echo "</form>";
	}
}

?>