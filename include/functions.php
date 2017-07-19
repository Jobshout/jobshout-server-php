<?php
function NewGuid() { 
	$s = strtoupper(md5(uniqid(rand(),true))); 
	$guidText = 
		substr($s,0,8) . '-' . 
		substr($s,8,4) . '-' . 
		substr($s,12,4). '-' . 
		substr($s,16,4). '-' . 
		substr($s,20); 
	return $guidText;
}

function UniqueGuid($tbl, $col) { 
	global $db;
	while(true){
		$guid= NewGuid();
		if($db->get_row("select * from ".$tbl." where ".$col." = '".$guid."'")){
			continue;
		}
		else{
			return $guid;
		}
	}
}
function set_session_values($page,$last_searched_keyword,$iDisplayStart,$iDisplayLength) { 
	$last_session_activity = array(
		"sSearch" => $last_searched_keyword, "iDisplayLength" => $iDisplayLength, "iDisplayStart" => $iDisplayStart
	);
	$_SESSION['last_search'][$page]=$last_session_activity;
}
?>