<?php 

require_once("PricirViewApp.php");
require_once("../Controller/PricirControllerForm.php") or die("Cound not find PricirControllerForm.php in the controller directory.");

class PricirForms extends PricirViewApp {
	private $forms = array();
	private $controller = new PricirControllerForm;
	
	public function displayCreateForm($action) {
		
		// if has priviledges
		if(is_admin()) { ?>
			
			<div class="create-price">
				<form method="post" action="<?php echo $action; ?>">
					<ul>
						<!-- This is what will go before the actual pricing that is displayed -->
						<li><input type="text" class="label" name="label" id="label" value="" /></li>
						<!-- The actual price that will be displayed -->
						<li><input type="text" class="price" name="price" id="price" value="" /></li>
				
					</ul>
					
					<input type="hidden" name="action" value="create-price" />
				</form>
			</div>
			
		<?php } // endif
	} // end function

	public function displayAllPrices() {
		global $prices;
		
		foreach($prices = $key => $value) {
			//code for displaying the prices. Write after Model is created
		}
	}
	
};



?>