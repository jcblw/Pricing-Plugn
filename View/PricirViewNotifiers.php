<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A Helper Class to handle notifications to be sent to the dashboard
 *
 * @author Jordan
 */

require_once ROOT_PATH . "/View/PricirViewApp.php";

class PricirViewNotifiers extends PricirViewApp {
	private $errMsg;
	private $errNo;
	private $flag;
	
	public function __construct($msg, $flag,  $errNo = NULL) {
		parent::__construct();
		
		$this->errMsg = $msg;
		$this->errNo = (isset($errNo)) ? $errNo : NULL;
		$this->flag = $flag;
	}
	
	public function tossNotif() {
		switch($this->flag) {
			case "info":
				$class = "updated";
			break;
			case "alert":
				$class = "error";
			break;
			default:
				$class = "updated";
			break;
		}?>
		<div id="message" class="<?php echo $class; ?> below-h2 fade" style="margin-top:30px; margin-left:5px; width:600px; cursor:pointer;" onclick="jQuery('div#message').css('display','none');">
		<p style="float:right; font-size:10px; font-variant:small-caps; color:#600000; padding-top:4px;">(close)</p>
		<p><b><?php echo (isset($this->errNo)) ? $this->errNo . " - " : ""; echo $this->errMsg; ?></b></p>
		</div>
		<?php
		if ($flag == "alert")
			die;
	}
	
	public static function staticTossNotif($msg, $flag, $errNo = NULL) {
		switch($flag) {
			case "info":
				$class = "updated";
			break;
			case "alert":
				$class = "error";
			break;
			default:
				$class = "updated";
			break;
		}?>
		<div id="message" class="<?php echo $class; ?> below-h2 fade" style="margin-top:30px; margin-left:5px; width:600px; cursor:pointer;" onclick="jQuery('div#message').css('display','none');">
		<p style="float:right; font-size:10px; font-variant:small-caps; color:#600000; padding-top:4px;">(close)</p>
		<p><b><?php echo (isset($errNo)) ? $errNo . " - " : ""; echo $msg; ?></b></p>
		</div>
		<?php
		
		if ($flag == "alert")
			die;
	}
	
}

?>
