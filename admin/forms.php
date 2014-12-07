<?php

function fInput($name="fInput", $value="", $type="text"){
	return "<input name=\"".$name."\" type=\"".$type."\" value=\"".$value."\" />";
}

function fHeader($title){
	if( $title )
		print "<h3>".$title."</h3>";
}

function fInfo($msg){
	if( $msg)
		print "<div class=\"info\">".$msg."</div>";
}

function tableStart(){
	print "<table>";
}

function tableEnd(){
	print "</table>";
}

function tableRow($a, $b=null, $c=null){
	if( $a ){
		if( $b ){
			print "<tr><td>".$a."</td><td>".$b.( $c != null ? " ".$c : "" )."</td></tr>\n";
		} else {
			print "<tr><td colspan=\"2\" align=\"center\">".$a."</td></tr>";
		}
	}
}

function tableRow4($a, $b=null, $c=null, $d=null){
	$t = "<tr>";
	if( $b == null && $c == null && $d == null ){
		$t .= "<td colspan=\"4\" align=\"center\">".$a."</td>";
	} else {
		if($a) $t .= "<td>".$a."</td>";
		if($b) $t .= "<td>".$b."</td>";
		if($c) $t .= "<td>".$c."</td>";
		if($d) $t .= "<td>".$d."</td>";
	}
	print $t."</tr>\n";
}

function formStart($action="index.php", $target=""){
	print "<form action=\"".$action."\" method=\"post\" target=\"".$target."\">";
}

function formEnd(){
	print "</form>";
}

?>