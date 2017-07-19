<?php
require_once("connect.php"); 

function createRandomString($string_length, $character_set) {
  $random_string = array();
  for ($i = 1; $i <= $string_length; $i++) {
    $rand_character = $character_set[rand(0, strlen($character_set) - 1)];
    $random_string[] = $rand_character;
  }
  shuffle($random_string);
  return implode('', $random_string);
}

function validUniqueString($new_string, $link_guid) {
	global $db;
  $query_chk= "select * from links where short_url='".$new_string."'";
	if(strlen($link_guid)>0) {
		$query_chk.= " and GUID<>'".$link_guid."'";
	}
	if($db->get_row($query_chk)) {
		return false;
	}
	else{
		return true;
	}
  return (strlen(strpos($combined_strings, $new_string))) ? false : true;
}

/*function validUniqueString($string_collection, $new_string, $existing_strings='') {
  if (!strlen($string_collection) && !strlen($existing_strings))
    return true;
  $combined_strings = $string_collection . ", " . $existing_strings;
  return (strlen(strpos($combined_strings, $new_string))) ? false : true;
}*/

/*function createRandomStringCollection($string_length, $number_of_strings, $character_set, $existing_strings = '') {
  $string_collection = '';
  for ($i = 1; $i <= $number_of_strings; $i++) {
    $random_string = createRandomString($string_length, $character_set);
    while (!validUniqueString($string_collection, $random_string, $existing_strings)) {
      $random_string = createRandomString($string_length, $character_set);
    }
    $string_collection .= ( !strlen($string_collection)) ? $random_string : ", " . $random_string;
  }
  return $string_collection;
}*/

$character_set = '23456789abcdefghjklmnpqrstuvwxyz';
$existing_strings = "";
$string_length = 3;
$guid='';
if(isset($_POST['guid']) && $_POST['guid']!='') {
	$guid= $_POST['guid'];
}

//$number_of_strings = 10;
//echo createRandomStringCollection($string_length, $number_of_strings, $character_set, $existing_strings);


if(isset($_POST['short_url']) && $_POST['short_url']!='') {
	if(!validUniqueString($_POST['short_url'], $guid)) {
		$data['msg']= $_POST['short_url']." already exists. Please enter another";
		$data['value']='';
	}
	else{
		$data['msg']= '';
		$data['value']=$_POST['short_url'];
	}
}
elseif(!isset($_POST['short_url'])) {
	
	$random_string = createRandomString($string_length, $character_set);
	while (!validUniqueString($random_string, $guid)) {
      $random_string = createRandomString($string_length, $character_set);
    }
	$data['msg']= '';
	$data['value']=$random_string.".html";;
}
echo json_encode($data);

?>
