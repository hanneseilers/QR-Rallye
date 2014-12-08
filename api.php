<?php

$STATUS_SEPERATOR = ":";
$SNIPPET_TIMEOUT = "SNIPPET_TIMEOUT";
$SNIPPET_EOA = "SNIPPET_EOA";
$SNIPPET_NOT_ONLINE ="SNIPPET_NOT_ONLINE";
$RALLYE_DONE = "RALLEY_DONE";
$SOLUTION_OK = "SOLUTION_OK";
$SOLUTION_FALSE = "SOLUTION_FALSE";

include 'db/db.php';

// Get parameters
$aFunction = -1;
if( isset($_GET['f'])  ){
	$aFunction = $_GET['f'];
}

$aRallyeID = null;
if( isset($_GET['rID']) ){
	$aRallyeID = $_GET['rID'];
}

$aGroupHash = null;
if( isset($_GET['gHash']) ){
	$aGroupHash = $_GET['gHash'];
}

$aGroupName = null;
if( isset($_GET['gName']) ){
	$aGroupName = $_GET['gName'];
}

$aSnippetNumber = null;
if( isset($_GET['n']) ){
	$aSnippetNumber = $_GET['n'];
}

$aSolution = null;
if( isset($_GET['S']) ){
	$aSolution = $_GET['S'];
}

/**
 * 	Select function
 * 	1	Get Rallye information		(rID)
 * 	2	Get next snippet from		(rID, gHash, gName, snippetNr)
 * 		unsolved item 		
 * 	3	Get number of items 		(rID)
 * 	4	Get number of solved items	(rID, gHash, gName)
 * 	5	Submit solution				(rID, gHash, gName, itemSolution)
 */
switch( $aFunction ){
	
	case 1:
		// Get Rallye information
		$vData = qr_getRallye($aRallyeID);
		if( $vData ) {
			unset( $vData[0]['rPassword'], $vData[0]['rMail'] );
			print json_encode( $vData[0] );
		}		
		break;
		
	case 2:
		print getPendingSnipped($aRallyeID, $aGroupHash, $aGroupName, $aSnippetNumber);		
		break;
		
	case 3:
		print sizeof(qr_getRallyeItems($aRallyeID));
		break;
	
	case 4:
		print sizeof(qr_getSolvedItems($aRallyeID, $aGroupHash));
		break;
		
	case 5:
		$vItem = qr_getPendingItem($aRallyeID, $aGroupHash);
		if( $aGroupName && $aGroupHash ){
			qr_updateGroup($gHash, $gName);
		}
		if( !$vItem || qr_submitSolution($aRallyeID, $aGroupHash, $vItem, $aSolution) )
			status($SOLUTION_OK);
		else status($SOLUTION_FALSE);
		break;
	
}

function getPendingSnipped($rID, $gHash, $gName, $snippedNr){
	global $SNIPPET_EOA, $SNIPPET_TIMEOUT, $RALLYE_DONE, $SNIPPET_NOT_ONLINE;
	
	// get pending, unsolved item and return snippet
	$vPendingItem = qr_getPendingItem($rID, $gHash);
	if( qr_addGroup($gHash, $gName) && $vPendingItem ){
			
		// check if group can get new snippet
		$vSolvedItems = qr_getSolvedSnippets($rID, $gHash);
		$vSolvedAt = 0;
		if( sizeof($vSolvedItems) > 0 ){
			$vSolvedAt = $vSolvedItems[0]['giSolvedAt'];
		}
		
		$vRallye = qr_getRallye($rID);
		if( $vSolvedAt >= 0 && $vRallye ){
			$vRallye = $vRallye[0];
			$vSolvedAt = strtotime($vSolvedAt);
			$vCurrentTime = date_timestamp_get(date_create());
			$vSnippetsDelay = $vRallye['rSnippetsDelay'];
			$vItem = qr_getItem($vPendingItem['qr_items_iID']);
			
			if( $vItem && strtotime($vItem[0]['iStart']) <= $vCurrentTime ){
				if( $vItem ){
					// get snippet
					$vItem = $vItem[0];
					$vSnippets = explode(";", $vItem['iSnippets']);
					if( $snippedNr != null && sizeof($vSnippets) > $snippedNr )
						return $vSnippets[$snippedNr];
					else
						status($SNIPPET_TIMEOUT);
				}
			} else {
				status($SNIPPET_NOT_ONLINE);
			}
		}
			
	} else {
		// no pending item
		status($RALLYE_DONE);
	}
}

/**
 * Shows status message.
 * @param string $msg
 */
function status($msg){
	global $STATUS_SEPERATOR;
	die( $STATUS_SEPERATOR.$msg );
}

?>