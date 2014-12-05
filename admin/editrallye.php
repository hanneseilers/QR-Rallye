<?php

require_once "../db/db.php";
require_once "forms.php";

// update rallye settings
if( isset($_POST['update']) ){
	
	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2 && isset($_POST['rMail']) ){
		
		// get rallye
		$vRallye = qr_getRallyeByName($_POST['rName'], $_POST['rMail']);
		
		if( $vRallye ){
			$vRallye = $vRallye[0];
			
			$rStart = $vRallye['rStart'];
			if( isset($_POST['rStart']) && strlen($_POST['rStart']) == 19 ){
				$rStart = $_POST['rStart'];
			}
			
			$rEnd = $vRallye['rEnd'];
			if( isset($_POST['rEnd']) && strlen($_POST['rEnd']) == 19 ){
				$rEnd = $_POST['rEnd'];
			}
			
			$rSnippetsDelay = $vRallye['rSnippetsDelay'];
			if( isset($_POST['rSnippetsDelay']) && strlen($_POST['rSnippetsDelay']) > 0 ){
				$rSnippetsDelay = $_POST['rSnippetsDelay'];
			}
			
			$rPassword = $vRallye['rPassword'];
			if( isset($_POST['rNewPassword']) && strlen($_POST['rNewPassword']) > 0 ){
				$rEnd = md5($_POST['rNewPassword']);
			}
			
			$rMail = $vRallye['rMail'];
			if( isset($_POST['rNewMail']) && filter_var($_POST['rNewMail'], FILTER_VALIDATE_EMAIL) ){
				$rMail = $_POST['rNewMail'];
			}
			
			$rName = $vRallye['rName'];
			if( isset($_POST['rNewName']) && strlen($_POST['rNewName']) > 2 ){
				$rName = $_POST['rNewName'];
			}
			
			$sql = "UPDATE qr_ralleys SET "
					."rName = '".$rName."',"
					."rStart = '".$rStart."',"
					."rEnd = ". ($rEnd != null ? "'".$rEnd."'" : "NULL") . ","
					."rSnippetsDelay = ".$rSnippetsDelay.","
					."rPassword = '".$rPassword."',"
					."rMail = '".$rMail."' "
					."WHERE rID = ".$vRallye['rID'];
			
			if( qr_dbSQL($sql) ){
				print "Rallye ".$rName." data saved";
				$_POST['rName'] = $rName;
				$_POST['rMail'] = $rMail;
			}
		}
		
	} else {
		print $_POST['rName']." ".$_POST['rMail'];
		print "Rallye name with min. 3 chars and correct mail adress is required";
	}
	
}

// update rallye settings
if( isset($_POST['addItem']) ){

	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2 && isset($_POST['rMail']) ){

		// get rallye
		$vRallye = qr_getRallyeByName($_POST['rName'], $_POST['rMail']);

		if( $vRallye ){
			$vRallye = $vRallye[0];
			
			$iSnippets = "";
			if( isset($_POST['iSnippets']) && strlen($_POST['iSnippets']) > 0 ){
				$iSnippets = $_POST['iSnippets'];
			}
			
			$iSolution = "";
			if( isset($_POST['iSolution']) && strlen($_POST['iSolution']) > 0 ){
				$iSolution = $_POST['iSolution'];
			}
			
			$iStart = date("Y-m-d G:i:s");
			if( isset($_POST['iStart']) && strlen($_POST['iStart']) == 19 ){
				$iStart = $_POST['iStart'];
			}
				
			$iEnd = null;
			if( isset($_POST['iEnd']) && strlen($_POST['iEnd']) == 19 ){
				$iEnd = $_POST['iEnd'];
			}
			
			// add item
			$sql = "INSERT INTO qr_items (iSnippets, iSolution, iStart, iEnd) "
					."VALUES ('".$iSnippets."',"
					."'".$iSolution."',"
					."'".$iStart."',"
					.( $iEnd != null ? "'".$iEnd."'" : "NULL" ).")";
			if( qr_dbSQL($sql) ){
				$vLastItem = qr_getLastItem();
				if( $vLastItem ){
					$sql = "INSERT INTO qr_ralleys_has_items (qr_ralleys_rID, qr_items_iID) "
							."VALUES (".$vRallye['rID'].",".$vLastItem['iID'].")";
					qr_dbSQL($sql);
				}
			}
		}
		
	}
	
}

// update item
if( isset($_POST['updateItem']) ){	
	if( isset($_POST['iID']) ){
		
		$vItem = qr_getItem($_POST['iID']);
		if( $vItem ){			
			$vItem = $vItem[0];
			
			if( isset($_POST['removeItem']) ){
				
				// remove item
				$sql = "DELETE FROM qr_groups_solved_items WHERE qr_items_iID = ".$vItem['iID'];
				qr_dbSQL($sql);
				$sql = "DELETE FROM qr_ralleys_has_items WHERE qr_items_iID = ".$vItem['iID'];
				qr_dbSQL($sql);
				$sql = "DELETE FROM qr_items WHERE iID = ".$vItem['iID'];
				qr_dbSQL($sql);
				
			} else {
				
				// update item
				$iSnippets = "";
				if( isset($_POST['iSnippets']) && strlen($_POST['iSnippets']) > 0 ){
					$iSnippets = $_POST['iSnippets'];
				}
					
				$iSolution = "";
				if( isset($_POST['iSolution']) && strlen($_POST['iSolution']) > 0 ){
					$iSolution = $_POST['iSolution'];
				}
					
				$iStart = $vItem['iStart'];
				if( isset($_POST['iStart']) && strlen($_POST['iStart']) == 19 ){
					$iStart = $_POST['iStart'];
				}
				
 				$iEnd = $vItem['iEnd'];
// 				if( isset($_POST['iEnd']) && strlen($_POST['iEnd']) == 19 ){
// 					$iEnd = $_POST['iEnd'];
// 				}
				
				$sql = "UPDATE qr_items SET "
						."iSnippets = '".$iSnippets."',"
						."iSolution = '".$iSolution."',"
						."iStart = '".$iStart."',"
						."iEnd = ".( $iEnd != null ? "'".$iEnd."'" : "NULL" )." "
						."WHERE iID = ".$vItem['iID'];
				qr_dbSQL($sql);
				
			}
			
		}
		
	}	
}

// edit rallye
if( isset($_POST['edit']) || isset($_POST['update'])
		|| isset($_POST['addItem']) || isset($_POST['updateItem']) ){

	if( isset($_POST['rName']) && isset($_POST['rMail']) ){

		$rPassword = null;
		if( isset($_POST['rPassword']) && strlen($_POST['rPassword']) > 0 ){
			$rPassword = $_POST['rPassword'];
		}

		// get rallye
		$vRallye = qr_getRallyeByName($_POST['rName'], $_POST['rMail']);
		if( $vRallye ){
			$vRallye = $vRallye[0];
				
			// check password
			if( md5($rPassword) == $vRallye['rPassword'] || $rPassword == $vRallye['rPassword'] ){

				// show rallye
				fHeader( "Rallye: ".$_POST['rName'] );
				formStart();
				tableStart();
				tableRow( "Rallye Name*:",  fInput("rNewName", $vRallye['rName']) );
				tableRow( "Rallye Start:", fInput("rStart", $vRallye['rStart']), "[YYYY-MM-DD hh:mm:ss]" );
// 				tableRow( "Rallye End:", fInput("rEnd", $vRallye['rEnd']), "[YYYY-MM-DD hh:mm:ss]" );
				tableRow( "Snippets delay:", fInput("rSnippetsDelay", $vRallye['rSnippetsDelay']) );
				tableRow( "E-Mail*: ", fInput("rNewMail", $vRallye['rMail']) );
				tableRow( "New Password: ", fInput("rNewPassword", "", "password") );
				tableRow( fInput("update", "Save Rallye", "submit") );
				tableEnd();
				print fInput("rName", $vRallye['rName'], "hidden");
				print fInput("rMail", $vRallye['rMail'], "hidden");
				print fInput("rPassword", $vRallye['rPassword'], "hidden");
				formEnd();
				
				fHeader("Generate QR codes");
				formStart("generateQR.php");
				print fInput("rID", $vRallye['rID'], "hidden");
				print "# of codes: ".fInput("n", "10")."<br />";
				print fInput("genQR", "Generate", "submit");
				formEnd();
				
				fHeader( "Items:" );
				showItems($vRallye);

			} else {
				print "Password not correct!";
			}
				
		} else {
			print "Rallye ".$_POST['rName']." not found.";
		}

	}

}



function showItems($rallye){	
	if( $rallye ){
		
		// get items
		$vItemIDs = qr_getRalleyItems($rallye['rID']);
		if( $vItemIDs ){
			
			foreach( $vItemIDs as $vItemID ){
				$vItem = qr_getItem($vItemID['qr_items_iID']);
				if( $vItem ){
					$vItem = $vItem[0];
					
					formStart();
					tableStart();
					tableRow4( 	"Snippets:", fInput("iSnippets", $vItem['iSnippets']),
								"Solution:",  fInput("iSolution", $vItem['iSolution']) );
// 					tableRow4(	"Start:", fInput("iStart", $vItem['iStart']),
// 								"End:", fInput("iEnd", $vItem['iEnd']) );
					tableRow4(	"Start:", fInput("iStart", $vItem['iStart']) );
					tableRow4( fInput("removeItem", "remove" ,"checkbox")."Remove item" );
					tableRow4( fInput("updateItem", "Update", "submit") );
					tableEnd();
					print fInput("iID", $vItem['iID'], "hidden");
					print fInput("rName", $rallye['rName'], "hidden");
					print fInput("rMail", $rallye['rMail'], "hidden");
					print fInput("rPassword", $rallye['rPassword'], "hidden");
					formEnd();
				}
			}
			
		} else {
			print "No items found.";
		}
		
		// add new item
		fHeader("New item");
		formStart();
		tableStart();
		tableRow("Snippets:", fInput("iSnippets"), "<small>(seperate by semicolon)</small>");
		tableRow("Solution:", fInput("iSolution"));
		tableRow("Start:", fInput("iStart"), "[YYYY-MM-DD hh:mm:ss]");
// 		tableRow("End:", fInput("iEnd"), "[YYYY-MM-DD hh:mm:ss]");
		tableRow( fInput("addItem", "Add New Item", "submit") );
		tableEnd();
		print fInput("rName", $rallye['rName'], "hidden");
		print fInput("rMail", $rallye['rMail'], "hidden");
		print fInput("rPassword", $rallye['rPassword'], "hidden");
		formEnd();
		
	}	
}


?>