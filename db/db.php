<?php

$dbDatabase = "35110m24661_3";
$dbUser = "35110m24661_3";
$dbPassword = "maxdxFni";
$dbHost = "localhost";
$log_enabled = false;

/**
 * Connect to database and execute SQL command.
 * @param string $sql	SQL command to execute.
 * @return array
 */
function qr_dbSQL($sql){	
	global $dbDatabase, $dbUser, $dbPassword, $dbHost, $log_enabled;
	
	$vHandle = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
	$vResult = mysqli_query($vHandle, $sql);
	
	if( $log_enabled ) print "<hr />".$sql."<hr />";

	if( gettype($vResult) == "boolean" ) return $vResult;
	else{
		$vReturn = array();
		while( ($row=mysqli_fetch_assoc($vResult)) ){
			array_push($vReturn, $row);	
		}
		return $vReturn;
	}
}

/**
 * Get information about ralley
 * @param integer $rID	Ralley number.
 * @return multitype:
 */
function qr_getRallye($rID){
	if( $rID )	
		return qr_dbSQL("SELECT * FROM qr_ralleys WHERE rID = ".$rID);
}

/**
 * Get information about ralley
 * @param string $rName
 * @param string $rMail
 * @return multiple
 */
function qr_getRallyeByName($rName, $rMail){
	if( $rName && $rMail )
		return qr_dbSQL("SELECT * FROM qr_ralleys WHERE rName = '".$rName."' AND rMail = '".$rMail."'");
}

/**
 * Gets the currently pending, unsolved item of a group in a ralley.
 * @param integer $rID	Rallley ID
 * @param integer $gID	Group ID
 * @return multitype:
 */
function qr_getPendingItem($rID, $gID){
	if( $rID && $gID ){
		// select item of group in ralley that is not solved
		$vResult = qr_dbSQL("SELECT qr_ralleys_has_items.qr_items_iID FROM qr_ralleys_has_items "
				."LEFT OUTER JOIN qr_groups_solved_items "
				."ON (qr_ralleys_has_items.qr_items_iID = qr_groups_solved_items.qr_items_iID) "
				."WHERE qr_groups_solved_items.qr_items_iID IS NULL "
				."AND qr_ralleys_has_items.qr_ralleys_rID = ".$rID." "
				."ORDER BY qr_ralleys_has_items.qr_items_iID ASC");
		if( $vResult ) return $vResult[0];
	}
}

/**
 * Gets an item
 * @param integer $iID	Item ID
 * @return multitype
 */
function qr_getItem($iID){
	if( $iID )
		return qr_dbSQL("SELECT * FROM qr_items WHERE iID = ".$iID);
}

/**
 * gety the last item.
 * @return multitype
 */
function qr_getLastItem(){
	$vItems = qr_dbSQL("SELECT * FROM qr_items ORDER BY iID DESC");
	if( $vItems )
		return $vItems[0];
}

/**
 * Checks if item is connected to a ralley.
 * @param integer $rID	Ralley ID
 * @param integer $iID	Item ID
 * @return boolean
 */
function qr_isItemInRalley($rID, $iID){
	if( $rID && $iID ){
		return sizeof( qr_dbSQL("SELECT * FROM qr_ralleys_has_items WHERE qr_ralleys_rID = ".$rID
			." AND qr_items_iID = ".$iID) ) > 0;
	}	
	return false;
}

/**
 * Checks if group exists
 * @param integer $gID
 * @return boolean
 */
function qr_existsGroup($gHash){
	if( $gHash )
		return sizeof(qr_dbSQL("SELECT * FROM qr_groups WHERE gHash = '".$gHash."'")) > 0;	
	return false;
}

/**
 * Updates group
 * @param integer $gID
 * @param string $gName
 * @return boolean
 */
function qr_updateGroup($gHash, $gName){
	if( $gHash && $gName )
		return qr_dbSQL("UPDATE qr_groups SET gName = '".$gName."' WHERE gHash = '".$gHash."'");	
	return false;
}

/**
 * Adds a new group or updates it if it already exists.
 * @param integer $gID
 * @param string $gName
 * @return boolean
 */
function qr_addGroup($gHash, $gName){
	
	if( $gHash && $gName ){
		if( qr_existsGroup($gHash) ){
			return qr_updateGroup($gHash, $gName);
		}
		else{
			return qr_dbSQL("INSERT INTO qr_groups (gHash, gName) VALUES ('".$gHash."', '".$gName."')");
		}
	}
	return false;
}

/**
 * Gets group ID from hash
 * @param string $gHash
 * @return integer
 */
function qr_getGroupID($gHash){
	if( $gHash ){
		$vResult = qr_dbSQL("SELECT * FROM qr_groups WHERE gHash = '".$gHash."'");
		if( $vResult )
			return $vResult[0]['gID'];
	}
	
	return -1;
}

/**
 * Gets the solved snippets of a group for a ralley.
 * @param integer $rID	Ralley ID
 * @param integer $gID	Group ID
 */
function qr_getSolvedSnippets($rID, $gHash){
	if( $rID && $gHash && ($gID = qr_getGroupID($gHash)) > 0 ){
		// get all solved items
		$vSolvedItems = qr_dbSQL("SELECT * FROM qr_groups_solved_items "
				."JOIN qr_ralleys_has_items "
				."ON qr_groups_solved_items.qr_items_iID =  qr_ralleys_has_items.qr_items_iID ".
				"WHERE qr_groups_solved_items.qr_groups_gID = ".$gID." "
				."ORDER BY qr_groups_solved_items.giSolvedAt DESC");
		if( $vSolvedItems )
			return $vSolvedItems;
	}
	
	return array();
}

/**
 * Gets item IDs of a ralley
 * @param integer $rID
 * @return array
 */
function qr_getRalleyItems($rID){
	if( $rID )
		return qr_dbSQL("SELECT qr_items_iID FROM qr_ralleys_has_items WHERE qr_ralleys_rID = ".$rID);
}

/**
 * Gets all solved items for a group in a ralley.
 * @param integer $rID
 * @param integer $gID
 * @return array
 */
function qr_getSolvedItems($rID, $gHash){
	if( $rID && $gHash && ($gID = qr_getGroupID($gHash)) > 0 )
		return qr_dbSQL("SELECT * FROM qr_groups_solved_items "
				."JOIN qr_ralleys_has_items "
				."ON qr_groups_solved_items.qr_items_iID = qr_ralleys_has_items.qr_items_iID "
				."WHERE qr_ralleys_has_items.qr_ralleys_rID = ".$rID." "
				."AND qr_groups_solved_items.qr_groups_gID = ".$gID);
}

/**
 * Submitting a solution for the current pending, unsolved item.
 * @param integer $rID		Ralley ID
 * @param integer $gID		Group ID
 * @param mixed $vItem		Item to add solution for.
 * @param string $solution	Solution
 * @return boolean
 */
function qr_submitSolution($rID, $gHash, $vItem, $solution){
	if( $rID && $gHash && $vItem && ($gID = qr_getGroupID($gHash)) ){
		$vItem = qr_getItem($vItem['qr_items_iID']);
			
		// check solution
		if( $vItem && $vItem[0]['iSolution'] == $solution ){
			$sql = "INSERT INTO qr_groups_solved_items(qr_groups_gID, qr_items_iID, giSolvedAt) "
					."VALUES (".$gID.", ".$vItem[0]['iID'].", now())";
			if( qr_dbSQL($sql) ) return true;
		}
	}
	
	return false;
}

?>