<?php 

require_once "../db/db.php";
require_once "forms.php";

// show groups rallye progress
if( isset($_GET['groups']) ){
	showGroups();
	unset($_GET['groups']);
	
	// get groups
	$sql = "SELECT gName, gHash FROM qr_groups";
	$vGroups = qr_dbSQL($sql);
	
	// get rallyes
	$sql = "SELECT rName, rID FROM qr_rallyes";
	$vRallyes = qr_dbSQL($sql);
	if( $vRallyes && $vGroups ){
		foreach($vRallyes as $vRallye){
			$rName = $vRallye['rName'];
			$rID = $vRallye['rID'];	

			fHeader( $rName );
			tableStart();
			tableRow( "<b>Group</b>", "<b>Progress</b>" );
			
			// get number of items
			$vItems = qr_getRallyeItems($rID);
			if( $vItems ){				
				// get solved items
				foreach($vGroups as $vGroup){
					$gHash = $vGroup['gHash'];
					$gName = $vGroup['gName'];
					$vSolvedItems = qr_getSolvedItems($rID, $gHash);
					$vProgress = count($vSolvedItems)/count($vItems);
					tableRow( $gName, ($vProgress*100)."%" );
				}																
			}
			
			tableEnd();
			
		}
	} else {
		fInfo( "No rallyes or groups found" );
	}
	
}

function showGroups(){
	echo '<script type="text/javascript">showElement("groups");</script>';
}

?>