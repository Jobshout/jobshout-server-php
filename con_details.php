<?php
	
if(isset($_POST['connect_to'])){
	//--unset last searched session on site switch--
	if(isset($_SESSION['last_search'])){ unset($_SESSION['last_search']); }
	
	setcookie('connect_to', $_POST['connect_to']);//bsw 20131128 removed , time()+60*60*24*365 connect to should only last user close browser
	$connect_to=$_POST['connect_to'];
}
elseif(isset($_COOKIE['connect_to'])){
	$connect_to=$_COOKIE['connect_to'];
}
else{
	$connect_to="Live";
}

$db_user="root";

if($connect_to=="Staging"){
	$db_host='localghost';
	$db_name='db';
	$db_pass='pass';
	$history_db_name='db_history';
}
elseif($connect_to=="Live"){
	
	$db_host='localghost';
	$db_name='db';
	$db_pass='pass';
	$history_db_name='db_history';

}
elseif($connect_to=="Dev"){
	$db_host='dev';
	$db_name='db';
	$db_pass='pass';
	$history_db_name='db_history';
}
	$db_host='localghost';
	$db_name='db';
	$db_pass='pass';
	$history_db_name='db_history';

?>