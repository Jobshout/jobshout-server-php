<?php
function set_session_values($page,$last_searched_keyword,$iDisplayStart,$iDisplayLength) { 
	$last_session_activity = array(
		"sSearch" => $last_searched_keyword, "iDisplayLength" => $iDisplayLength, "iDisplayStart" => $iDisplayStart
	);
	$_SESSION['last_search'][$page]=$last_session_activity;
}	
?>
