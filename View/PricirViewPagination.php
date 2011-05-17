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
		
		if ($increment > $this->totalAmt) {
			die('Error, Pagination increment greater than total amount.');
		} else {
			$this->increment = $increment;
		
		// The total number of pages that we should have
		$totalLinkAmt = ceil($totalAmt/$increment);
		$this->totalLinkAmt = $totalLinkAmt;
		
		if ($linkBuffer > floor($totalLinkAmt/2)) {
			die("Error, Pagination link buffer should be less than half the total number of pages");
		} else {
			$this->linkBuffer = $linkBuffer;
		}
	}
	
	// Needs lots of work.
	public function createNav() {
		$currentLink = $this->currentLink;
		$currentOffset = $this->currentOffset;
		$totalLinkAmt = $this->totalLinkAmt;
		$linkBuffer = $this->linkBuffer;
		
		$linkDifference = $currentLink - $linkBuffer;
		$linkSum = $currentLink + $linkBuffer;
	
		echo "<div class='pricir-pagination'>";
		echo "<ul class='pricir-pagination-list'>";
			
			$rewindParams = array( ($currentLink - 1), ($currentOffset - $increment) );
			$this->createRewindLinks($rewindParams[0], $rewindParams[1]);
		
			// Display the links before our current link
			if ($linkDifference < $linkBuffer) {
				$newOffset = 0;
				
				for ($i = 1; $i < $linkDifference; $i++) {
					$newOffset = $i * $increment;
					echo "<li><a alt='page-$id' id='page-$id' class='pricir-pagination-nav' href='?offset=$newOffset&page=$i'>$i</a></li>'";
				}
			} else {
				$newOffset = 0;
			
				for ($i = $linkDifference; $i < $currentLink; $i++) {
					$newOffset = $i * $increment;
					echo "<li><a alt='page-$id' id='page-$id' class='pricir-pagination-nav'  href='?offset=$newOffset&page=$i'>$i</a></li>'";
				}
			}
			
			// Display the current link
			echo "<li><p>" . $currentLink . "</p></li>";
			
			//  Display the links after our current link
			if ($linkSum > $totalLinkAmt) {
				$newOffset = 0;
				
				for($i = $currentLink + 1; $i < $totalLinkAmt; $i++) {
					$newOffset = $i * $increment;
					echo "<li><a  alt='page-$id' id='page-$id' class='pricir-pagination-nav' href= '?offset=$newOffset&page=$i' >$i</a></li>";
				}
			} else {
				$newOffset = 0;
				
				for($i = $currentLink + 1; $i < $linkSum; $i++) {
					$newOffset = $i * $increment;
					echo "<li><a alt='page-$id' id='page-$id' class='pricir-pagination-nav'  href= '?offset=$newOffset&page=$i' >$i</a></li>";
				}
			}
			
			$forwardParams = array( ($currentLink + 1), ($currentOffset + $increment) ) ;
			$this->createForwardLinks($forwardParams[0], $forwardParams[1] );
			
		echo "</ul>";
		
		echo "<div class='pricir-pagination-position' ><p class='pricir-pagination-position-text'>Page $currentLink of $totalLinkAmt</p></div>";
		echo "</div>";
	}

	public function setCurrentOffset($currentOffset) {
		$this->currentOffset = $currentOffset;
	}
	
	public function getIncrement() {
		return $this->increment;
	}
	
	public function getLinkBuffer() {
		return $this->linkBuffer;
	}
	
	public function getTotalAmt() {
		$totalLinkAmt = $this->totalLinkAmt;
		$increment = $this->increment;
		
		$totalAmt = $totalLinkAmt * $increment;
	
		return $totalAmt;
	}
	
	public function getCurrentOffset() {
		return $this->currentOffset;
	}
	
	public function getCurretLink() {
		return $this->currentLink;
	}
	
	//Private functions
	private function createRewindLinks($rewindLink, $rewindOffset) {
		$currentLink = $this-currentLink;
		
		if($currentLink != 1) {
			echo "<li><a alt='first page' id='page-first' class='pricir-pagination-nav'  href=' ' >first</a></li>' ";
			echo "<li><a alt='previous page' id='page-prev' class='pricir-pagination-nav'  href='?offset=$rewindOffset&page=$rewindLink''>prev</a></li>' ";
		}
	}
	
	private function createForwardLink($forwardLink, $forwardOffset) {
		$totalLinkAmt = $this->totalLinkAmt;
		$currentLink = $this-currentLink;
		$increment = $this->increment;
		
		$totalOffset = $totalLinkAmt * $increment;
		
		if($currentLink != $totalLinkAmt) {
			echo "<li><a alt='next page' id='page-next' class='pricir-pagination-nav'  href= '?offset=$forwardOffset&page=$forwardLink' >next</a></li>'";
			echo "<li><a alt='last page' id='page-last' class='pricir-pagination-nav'  href= '?offset=$totaloffset&page=$totalLinkAmt' ''>last</a></li>'";
		}
	}
}

?>