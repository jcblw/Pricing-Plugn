<?php

class PricirViewPagination extends PricirViewApp {
	
	private $totalAmt;					// Total amount of actual items
	private $totalLinkAmt;				// Total amount of links/pages
	private $increment;				// Amount of items per page
	private $linkBuffer;					// Amount of Links before and after our current location
	private $currentOffset;			// Current Offset from zero. In other words, our Link location * our increment
	private $currentLink;				// The current link location.
	
	public function __construct($totalAmt, $increment = '10', $linkBuffer = '3') {
		
		if ( isset($_GET['page']) && isset($_GET['offset']) ) {
			$this->currentLink = $_GET['page'];
			$this->curretOffset = $_GET['offset'];
		} else {
			$this->currentLink = 0;
			$this->curretOffset = 0;
		}
		
		$this->totalAmt = $totalAmt;
		$this->T
		
		if ($increment > $this->totalAmt) {
			die('Error, Pagination increment greater than total amount.');
		} else {
			$this->increment = $increment;
		}
		
		// The total number of pages that we should have
		$totalLinkAmt = ceil($totalAmt/$increment);
		$this->totalLinkAmt = $totalLinkAmt;
		
		if ($linkBuffer > floor($totalLinkAmt/2)) {
			die("Error, Pagination link buffer should be less than the total number of pages");
		} else {
			$this->linkBuffer = $linkBuffer;
		}
	}
	
	// Needs lots of work.
	public function createNav($pricesArray) {
		echo "<ul>";
			// Display the links before our current link
			if (($this->currentLink - $this->linkBuffer) < 1) {
				$linksBefore = ($this->currentLink - $this->linkBuffer);
				$offsetBefore = 0;
				
				for ($i = 1; $i < $linksBefore; $i++) {
					$offsetBefore = $i * $this->increment;
					echo "<li><a href='?offset=$offsetBefore&page=$i'>$i</a></li>'";
				}
			} else {
				for ($i = 0; $i < $this->linkBuffer; $i++) {
					$offsetBefore = $i * $this->increment;
				}
			}
			
			//Display the current link
			echo "<li><p>" . $this->currentLink . "</p></li>";
			if ( ($this->currentLink +$this->linkBuffer) < 1) {
				for($i = 0; $i < $linkBuffer; $i++) {
					echo "<li><a href='?offset' "
				}
		echo "</ul>";
	}

	public function setCurrentOffset($currentOffset) {
		$this->currentOffset = $currentOffset;
	}
}

?>