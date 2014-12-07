
<?php

require_once "../db/db.php";
require_once "forms.php";

// update rallye settings
if( isset($_POST['update']) ){
	show();
	
	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2 && isset($_POST['rMail']) ){
		
		// get rallye
		$vrallye = qr_getrallyeByName($_POST['rName'], $_POST['rMail']);
		
		if( $vrallye ){
			$vrallye = $vrallye[0];
			
			$rSnippetsDelay = $vrallye['rSnippetsDelay'];
			if( isset($_POST['rSnippetsDelay']) && strlen($_POST['rSnippetsDelay']) > 0 ){
				$rSnippetsDelay = $_POST['rSnippetsDelay'];
			}
			
			$rPassword = $vrallye['rPassword'];
			if( isset($_POST['rNewPassword']) && strlen($_POST['rNewPassword']) > 0 ){
				$rEnd = md5($_POST['rNewPassword']);
			}
			
			$rMail = $vrallye['rMail'];
			if( isset($_POST['rNewMail']) && filter_var($_POST['rNewMail'], FILTER_VALIDATE_EMAIL) ){
				$rMail = $_POST['rNewMail'];
			}
			
			$rName = $vrallye['rName'];
			if( isset($_POST['rNewName']) && strlen($_POST['rNewName']) > 2 ){
				$rName = $_POST['rNewName'];
			}
			
			$sql = "UPDATE qr_rallyes SET "
					."rName = '".$rName."',"
					."rSnippetsDelay = ".$rSnippetsDelay.","
					."rPassword = '".$rPassword."',"
					."rMail = '".$rMail."' "
					."WHERE rID = ".$vrallye['rID'];
			
			if( qr_dbSQL($sql) ){
				fInfo( "rallye ".$rName." data saved" );
				$_POST['rName'] = $rName;
				$_POST['rMail'] = $rMail;
			}
		}
		
	} else {
		fInfo( $_POST['rName']." ".$_POST['rMail'] );
		fInfo( "rallye name with min. 3 chars and correct mail adress is required" );
	}
	
}

// remove rallye
if( isset($_POST['remove']) ){
	show();
	
	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2 && isset($_POST['rMail']) ){
	
		// get rallye
		$vrallye = qr_getrallyeByName($_POST['rName'], $_POST['rMail']);
	
		if( $vrallye ){
			$vrallye = $vrallye[0];
			$rID = $vrallye['rID'];
			
			// delete items
			$vItems = qr_getRallyeItems($rID);
			$ret = true;
			foreach( $vItems as $vItem ){
				$iID = $vItem['qr_items_iID'];
				if( $iID ){
					$sql = "DELETE FROM qr_groups_solved_items WHERE qr_items_iID = ".$iID;
					if( !qr_dbSQL($sql) ){
						$ret = false;
						break;
					}
					
					$sql = "DELETE FROM qr_rallyes_has_items WHERE qr_items_iID = ".$iID;
					if( !qr_dbSQL($sql) ){
						$ret = false;
						break;
					}
					
					$sql = "DELETE FROM qr_items WHERE iID = ".$iID;
					if( !qr_dbSQL($sql) ){
						$ret = false;
						break;
					}
				}
			}
			
			// check if successfull
			if( !$ret ){
				fInfo( "Deleting rallye items failed! Removing rallye canceled." );
			} else {
				fInfo( "rallye items deleted." );
				
				// remove rallye
				$sql = "DELETE FROM qr_rallyes WHERE rID = ".$rID;
				if( qr_dbSQL($sql) ){
					fInfo( "Removed rallye ".$vrallye['rName'] );
				} else {
					fInfo( "Removeing rallye ".$vrallye['rName']." failed!" );
				}
			}
			
		}
	} else {
		fInfo( "Removing rallye ".$_POST['rName']." (".$_POST['rMail'].") failed!" );
	}
}

// add rallye item
if( isset($_POST['addItem']) ){
	show();

	if( isset($_POST['rName']) && strlen($_POST['rName']) > 2 && isset($_POST['rMail']) ){		

		// get rallye
		$vrallye = qr_getrallyeByName($_POST['rName'], $_POST['rMail']);

		if( $vrallye ){
			$vrallye = $vrallye[0];
			
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
			
			// add item
			$sql = "INSERT INTO qr_items (iSnippets, iSolution, iStart) "
					."VALUES ('".$iSnippets."',"
					."'".$iSolution."',"
					."'".$iStart."')";
			if( qr_dbSQL($sql) ){
				$vLastItem = qr_getLastItem();
				if( $vLastItem ){
					$sql = "INSERT INTO qr_rallyes_has_items (qr_rallyes_rID, qr_items_iID) "
							."VALUES (".$vrallye['rID'].",".$vLastItem['iID'].")";
					if( qr_dbSQL($sql) ){
						fInfo( "Item added" );
					} else {
						fInfo( "Adding item failed!" );
					}
				}
			}
		}
		
	} else {
		fInfo( "Adding item failed!" );
	}
	
}

// update item
if( isset($_POST['updateItem']) && isset($_POST['iID']) ){	
	show();
		
	$vItem = qr_getItem($_POST['iID']);
	if( $vItem ){			
		$vItem = $vItem[0];
			
		if( isset($_POST['removeItem']) ){
			
			// remove item
			$sql = "DELETE FROM qr_groups_solved_items WHERE qr_items_iID = ".$vItem['iID'];
			qr_dbSQL($sql);
			$sql = "DELETE FROM qr_rallyes_has_items WHERE qr_items_iID = ".$vItem['iID'];
			qr_dbSQL($sql);
			$sql = "DELETE FROM qr_items WHERE iID = ".$vItem['iID'];
			if( qr_dbSQL($sql) ){
				fInfo( "Item removed" );
			} 
			
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
				
			$sql = "UPDATE qr_items SET "
					."iSnippets = '".$iSnippets."',"
					."iSolution = '".$iSolution."',"
					."iStart = '".$iStart."'"
					."WHERE iID = ".$vItem['iID'];
			if( qr_dbSQL($sql) ){
				fInfo( "Item updated" );
			} else {
				fInfo( "Updating item failed!" );
			}
				
		}
		
	}	
}

// edit rallye
if( isset($_POST['edit']) || isset($_POST['update'])
		|| isset($_POST['addItem']) || isset($_POST['updateItem']) ){
	show();

	if( isset($_POST['rName']) && isset($_POST['rMail']) ){

		$rPassword = null;
		if( isset($_POST['rPassword']) && strlen($_POST['rPassword']) > 0 ){
			$rPassword = $_POST['rPassword'];
		}

		// get rallye
		$vrallye = qr_getrallyeByName($_POST['rName'], $_POST['rMail']);
		if( $vrallye ){
			$vrallye = $vrallye[0];
				
			// check password
			if( md5($rPassword) == $vrallye['rPassword'] || $rPassword == $vrallye['rPassword'] ){

				// show rallye
				fHeader( "Edit rallye ".$_POST['rName'] );
				tableStart();
				
				formStart();
				tableRow( "rallye Name*:",  fInput("rNewName", $vrallye['rName']) );
				tableRow( "Snippets delay:", fInput("rSnippetsDelay", $vrallye['rSnippetsDelay']) );
				tableRow( "E-Mail*: ", fInput("rNewMail", $vrallye['rMail']) );
				tableRow( "New Password: ", fInput("rNewPassword", "", "password") );
				tableRow( fInput("update", "Save rallye", "submit") );
				print fInput("rName", $vrallye['rName'], "hidden");
				print fInput("rMail", $vrallye['rMail'], "hidden");
				print fInput("rPassword", $vrallye['rPassword'], "hidden");
				formEnd();
				
				formStart();
				print fInput("rName", $vrallye['rName'], "hidden");
				print fInput("rMail", $vrallye['rMail'], "hidden");
				print fInput("rPassword", $vrallye['rPassword'], "hidden");
				tableRow( fInput("remove", "Remove rallye", "submit") );
				formEnd();
				
				tableEnd();
				
				fHeader("Generate QR codes");
				formStart("generateQR.php", "_blanc");
				print fInput("rID", $vrallye['rID'], "hidden");
				print "# of codes: ".fInput("n", "10")."<br />";
				print fInput("genQR", "Generate", "submit");
				formEnd();
				
				fHeader( "Items:" );
				showItems($vrallye);

			} else {
				fInfo( "Password not correct!" );
			}
				
		} else {
			fInfo( "rallye ".$_POST['rName']." not found." );
		}

	}

}



function showItems($rallye){	
	if( $rallye ){
		show();
		
		// get items
		$vItemIDs = qr_getRallyeItems($rallye['rID']);
		if( $vItemIDs ){
			
			foreach( $vItemIDs as $vItemID ){
				$vItem = qr_getItem($vItemID['qr_items_iID']);
				if( $vItem ){
					$vItem = $vItem[0];
					
					formStart();
					tableStart();
					tableRow4( 	"Snippets:", fInput("iSnippets", $vItem['iSnippets']),
								"Solution:",  fInput("iSolution", $vItem['iSolution']) );
					tableRow4(	"Start:", fInput("iStart", $vItem['iStart']), "<small>[YYYY-MM-DD hh:mm:ss]</small>" );
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
			fInfo( "No items found." );
		}
		
		// add new item
		$iStart = date("Y-m-d G:i:s");
		fHeader("New item");
		formStart();
		tableStart();
		tableRow("Snippets:", fInput("iSnippets"), "<small>(seperate by semicolon)</small>");
		tableRow("Solution:", fInput("iSolution"));
		tableRow( "Start:", fInput("iStart", $iStart), "<small>[YYYY-MM-DD hh:mm:ss]</small>" );
		tableRow( fInput("addItem", "Add New Item", "submit") );
		tableEnd();
		print fInput("rName", $rallye['rName'], "hidden");
		print fInput("rMail", $rallye['rMail'], "hidden");
		print fInput("rPassword", $rallye['rPassword'], "hidden");
		formEnd();
		
	}	
}

function show(){
	echo '<script type="text/javascript">showElement("editcontent");</script>';
}


?>