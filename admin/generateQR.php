<?php

require_once "../phpqrcode/qrlib.php";

if( isset($_POST['rID']) && isset($_POST['n']) ){
	
	$vUrl = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
	$vUrl = substr($vUrl, 0, strpos($vUrl, "admin/"))."api.php";
	
	for( $n=0; $n < $_POST['n']; $n++ ){
		$vData = "{'url':'".$vUrl."','rID':".$_POST['rID'].",'n':".$n."}";
		QRcode::png($vData, "code".$rID.$n.".png",QR_ECLEVEL_M, 10);
		print "<img src=\"code".$rID.$n.".png\"><br />";
	}
	
}

?>