<?php
function set_session_values($page,$last_searched_keyword,$iDisplayStart,$iDisplayLength) { 
	$last_session_activity = array(
		"sSearch" => $last_searched_keyword, "iDisplayLength" => $iDisplayLength, "iDisplayStart" => $iDisplayStart
	);
	$_SESSION['last_search'][$page]=$last_session_activity;
}	

function set_session_query($page,$queryStr,$total_records_found, $startLimit, $endLimit) { 
	$_SESSION['last_search'][$page]["sQuery"]=$queryStr;
	$_SESSION['last_search'][$page]["total_records"]=$total_records_found;
	$_SESSION['last_search'][$page]["start_limit"]=$startLimit;
	$_SESSION['last_search'][$page]["end_limit"]=$endLimit;
}	
?>
