<?php

require_once "../db/db.php";

// new rallye
if( isset($_POST['new']) ){
	
	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2
			&& isset($_POST['rMail']) && filter_var($_POST['rMail'], FILTER_VALIDATE_EMAIL) ){
		
		$rStart = date("Y-m-d G:i:s");
		if( isset($_POST['rStart']) && strlen($_POST['rStart']) == 19 ){
			$rStart = $_POST['rStart'];
		}
		
		$rEnd = null;
		if( isset($_POST['rEnd']) && strlen($_POST['rEnd']) == 19 ){
			$rEnd = $_POST['rEnd'];
		}
		
		$rSnippetsDelay= 0;
		if( isset($_POST['rSnippetsDelay']) && strlen($_POST['rSnippetsDelay']) > 0 ){
			$rSnippetsDelay = $_POST['rSnippetsDelay'];
		}
		
		$rPassword = null;
		if( isset($_POST['rPassword']) && strlen($_POST['rPassword']) > 0 ){
			$rPassword = $_POST['rPassword'];
		}
		
		$sql = "INSERT INTO qr_rallyes (rName, rStart, rEnd, rSnippetsDelay, rPassword, rMail) "
				."VALUES ("
				."'".$_POST['rName']."',"
				."'".$rStart."',"
				."". ($rEnd != null ? "'".$rEnd."'" : "NULL") .","
				."".$rSnippetsDelay.","
				."'".md5($rPassword)."',"
				."'".$_POST['rMail']."') "
				."ON DUPLICATE KEY UPDATE rName = '".$_POST['rName']."' AND rMail = '".$_POST['rMail']."'";
		
		if( qr_getRallyeByName($_POST['rName'], $_POST['rMail']) == null ){
			if( qr_dbSQL($sql) ){
				print "Rallye ".$_POST['rName']." (".$_POST['rMail'].") added";
			}
		} else {
			print "Rallye ".$_POST['rName']." (".$_POST['rMail'].") is already there.";
		}
		
	} else {
		print "Rallye name with min. 3 chars and correct mail adress is required";
	}
	
}

?>