<?php 

require_once ROOT_PATH . "/View/PricirViewApp.php";

class PricirViewForm extends PricirViewApp {
	
	public function __construct() { }

	// Update this later to include attachments with the thumbnails
	public function createInsertNewItemForm($groupArray= NULL) {
		
		echo "<div class='pricir-item-insert'>";
		echo "<h2>Insert A New Item</h2>";
		?>
		
		<form method="POST"> 
			<label for="item-name">What is the name of this new item? </label> <input type="text" name="item-name" value="" class="pricir-input" />
			<label for="item-price">How much does it cost? </label> <input type="text" name="item-price" value="" class="pricir-input" />
			<label for="item-url">Thumnail URL? </label> <input type="text" name="item-url" value="" class="pricir-input" />
			
			<label for="item-group">Which group does this item belong to? </label>
			<select name ="item-group">
			<?php if (isset($groupArray)) { 
			foreach ($groupArray as $group) { ?>
				<option value="<?php echo $group['group_id']; ?>"><?php echo $group['name'] ?></option>
			<?php } } else { ?>
				<option value="">-- no groups exist, create a new group --</option>
			<?php } ?>
			</select>
			
			<h3>Or, create a new group for this item?</h3>
			<label for="new-item-group">name? </label>
			<input type="text" name="new-item-group" value="" class="pricir-input" />
			
			<input type="hidden" name="action" value="insert-item" />
			<input type="submit" class="button-primary" name="submit" value="submit" />
		</form>

		<?php
		echo "</div>";
	}
	
	// add opt groups
	public function createInsertNewOptionForm($itemArray, $groupReferenceArray) {
		
		echo "<div class='pricir-option-insert'>";
		echo "<h2>Create a new Option Set: </h2>";
		?>
		
		<form method="POST"> 
			<label for="option-lov-name">A name for this list of options  </label> <input type="text" name="option-name" value="" class="pricir-input" />
			
			<label for="option-item">The Item this option will affect </label>
			<select name ="option-item">
			<?php 
			if (isset($itemArray) && isset($groupReferenceArray) ) { 
			foreach ($groupReferenceArray as $k => $group_info_array) {
			?>
				<optgroup label="<?php echo $group_info_array['name']; ?>">
			<?php 
			foreach ($itemArray as $_k => $item) {
			if ($item['group_id'] === $group_info_array['group_id']) {
			?>
				<option value="<?php echo $item['item_id']; ?>"><?php echo $item['name']; ?></option>
			<?php } } ?>
				</optgroup>
			<?php } } else {  ?>
				<!--  a link to the insert items page -->
			<?php } ?>
			</select>
			
			<input type="hidden" name="action" value="insert-option" />
			<input type="submit" class="button-primary" name="submit" value="submit" />
		</form>

		<?php
		echo "</div>";
	}
	
	public function createInsertOptionDtlForm($option_id) {
		echo "<div class='pricir-option-insert'>"; 
	?>
		<form method="POST"> 
			<label for="option-dtl-name">A name for the option</label> <input type="text" name="option-lov-name" value="" class="pricir-input" />
			
			<label for="option-dtl-operator">How this option will modify price</label>
			<select name="option-dtl-operator" class="pricir-input">
				<option value="add">(+) - adds to the base price</option>
				<option value="subtract">(-) - subtracts from the base price</option>
				<option value="multiply">(*) - multiplies the base price</option>
				<option value="divide">(/) - divides the base price</option>
			</select>
			
			<label for="option-dtl-price-change">By how much this option will change the initial price </label> <input type="text" name="option-dtl-price-change" value="" class="pricir-input" />
			
			<input type="hidden" name="option-id" value="<?php echo $option_id; ?>" />
			<input type="hidden" name="action" value="insert-option-dtl" />
		</form>
	<?php
		echo "</div>";
	}
	
}

?>