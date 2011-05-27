<?php

require_once('/PricirViewApp.php');

class PricirViewPagination extends PricirViewApp {
	
	private $totalAmt;					// Total amount of actual items
	private $totalLinkAmt;				// Total amount of links/pages
	private $increment;				// Amount of items per page
	private $linkBuffer;					// Amount of Links before and after our current location
	private $currentOffset;			// Current Offset from zero. In other words, our Link location * our increment
	private $currentLink;				// The current link location.
	private $page;

	public function __construct($totalAmt, $increment = 10, $linkBuffer = 3) {
		
		$this->totalAmt = $totalAmt;
		$this->page = $_GET['page'];
		
		if ( ($_GET['pricir-page'] > 1 && isset($_GET['pricir-page'])) && ($_GET['pricir-offset'] > 1 && isset($_GET['pricir-offset'])) ) {
			$this->currentLink = $_GET['pricir-page'];
			$this->currentOffset = $_GET['pricir-offset'];
		} else {
			$this->currentLink = 1;
			$this->currentOffset = 1;
		}
		
		if ($increment > $this->totalAmt) {
			die('Error, Pagination increment greater than total amount.');
		} else {
			$this->increment = $increment;
		
			// The total number of pages that we should have
			$totalLinkAmt = ceil($totalAmt/$increment);
			$this->totalLinkAmt = $totalLinkAmt;
		}
		
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
		
		$this->createRewindLinks();
		
			// Display the links before our current link
			if ($linkDifference < $linkBuffer) {
				$newOffset = 0;
				
				for ($i = 1; $i < $currentLink; $i++) {
					$newOffset = $i * $increment;
					$this->createLink($newOffset, $i, "$i", "pricir-page-$i", "Go to page $id");
				}
			} else {
				$newOffset = 0;
			
				for ($i = $linkDifference; $i < $currentLink; $i++) {
					$newOffset = $i * $increment - $increment;
					$this->createLink($newOffset, $i, "$i", "pricir-page-$i", "Go to page $id");
				}
			}
			
			// Display the current link
			echo "<li><p class='pricir-pagination-nav' id='pricir-current-link'>" . $currentLink . "</p></li>";
			
			//  Display the links after our current link
			if ($linkSum > $totalLinkAmt) {
				$newOffset = 0;
				
				for($i = $currentLink + 1; $i <= $totalLinkAmt; $i++) {
					$newOffset = $i * $increment + $increment;
					$this->createLink($newOffset, $i, "$i", "pricir-page-$i", "Go to page $id");
				}
			} else {
				$newOffset = 0;
				
				for($i = ($currentLink + 1); $i <= $linkSum; $i++) {
					$newOffset = $i * $increment + $increment;
					$this->createLink($newOffset, $i, "$i", "pricir-page-$i", "Go to page $id");
				}
			}
			
		$this->createForwardLinks();
			
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
		return (int) $this->currentOffset;
	}
	
	public function getCurretLink() {
		return $this->currentLink;
	}
	
	//Private functions
	private function createRewindLinks() {

		$currentLink = $this->currentLink;
		$increment = $this->increment;
		$newOffset = (($currentLink - 1)*$increment == $increment) ? $newOffset = 1 : $newOffset = ($currentLink - 1) * $increment;
		
		if ($currentLink <= $this->totalLinkAmt && $currentLink != 1) {
			$this->createLink($newOffset, ($currentLink - 1), "prev page", "pricir-prev-page", "Go to the previous page");
			$this->createLink(1, 1, "first page", "pricir-first-page", "Go to the first page");
		} else {
			return;
		}
	}
	
	private function createForwardLinks() {
		
		$currentLink = $this->currentLink;
		$increment = $this->increment;
		$newOffset = $currentLink*$increment;
		
		if ($currentLink == 1 || $currentLink != $this->totalLinkAmt) {
			$this->createLink($newOffset, ($currentLink + 1), "next page", "pricir-next-page", "Go to the next page");
			$this->createLink(($this->totalAmt - $increment), $this->totalLinkAmt, "last page", "pricir-last-page", "Go to the last page");
		} else {
			return;
		}
	}
	
	private function createLink($offset, $page, $title, $id, $alt = "",  $class="pricir-pagination-nav") {
		echo "<li><a alr='$alt' id='$id' class='$class' href='?page=$this->page&pricir-offset=$offset&pricir-page=$page'>$title</a></li>";
	}
}

?>