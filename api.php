<?php 
ini_set('max_execution_time', 300);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once("connect.php");
require_once "include/JWT.php";

function utf8_encode_deep(&$input) {
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
}
function check_authentication($token){
	global $db;
	$JWT = new JWT;
	$key = '46196053844814367107123';
	$jwtJsonStr = $JWT->decode($token, $key);
	$jwtObject =json_decode($jwtJsonStr);
	$authenticatebool=false;
	if(isset($jwtObject->username) && $jwtObject->username!="" && isset($jwtObject->password) && $jwtObject->password!=""){
		$encrypted_mypassword= md5($jwtObject->password);
		$LoginQuery = "SELECT * FROM wi_users WHERE code='".$jwtObject->username."' AND password='$encrypted_mypassword' and status='1'";
		$LoginData = $db->get_row($LoginQuery);
		if($db->num_rows>0){
			$authenticatebool=true;
		}
	}
	return $authenticatebool;
}

$output=array();
$action= isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$module= isset($_REQUEST['module']) ? $_REQUEST['module'] : "";
$start= isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$limit= isset($_REQUEST['end']) ? $_REQUEST['end'] : 10;
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : "";

function getColumnType ($tableName, $columnName){
	global $db;
	$resultVal="";
	$query="select DATA_TYPE from information_schema.columns where table_name = '$tableName' and column_name = '$columnName'";
	if($result= $db->get_row($query)){
		$resultVal= $result->DATA_TYPE;
	}
	return $resultVal;
}

if(check_authentication($token)){
	if($module!=""){
		if($action=="get"){
			$findquery="select * from ".$module." LIMIT ".$start.", ".$limit;
			$recordsFound=$db->get_results($findquery);
			if(count($recordsFound)>0){
				$aaDataObject = array(); $count=0;
				$output['iTotalRecords']=count($recordsFound);
 				foreach($recordsFound as $fetchedRow){
 					$result=array();
 					foreach($fetchedRow as $key=>$value){
 						if(getColumnType($module, $key)=="longblob"){
 							$result[$key]= base64_encode($value);
 						}else{
 							$result[$key]= $value;
 						}
 					}
 					$aaDataObject[$count]=$result;
 					$count++;
 				}
 				$output['aaData']=$aaDataObject;
			} else {
				$output['error']="Sorry, no records found!";
			}
		}	elseif($action=="put"){
			// put data
			$file_path='php://input';
			$data= file_get_contents($file_path);
			if($data!=""){
				$data = gzuncompress($data);
				header("Content-type: application/json");
				$arr_tbl_data=json_decode($data); /
			
			//if(isset($_REQUEST['data']) && $_REQUEST['data']!=""){
				//$arr_tbl_data=json_decode($_REQUEST['data']);
				if(count($arr_tbl_data)>0){
						if(isset($_GET['updatecol']) && $_GET['updatecol']!=""){
							$updatecol=$_GET['updatecol'];
							if(getColumnType($module, $updatecol)=="bigint" || getColumnType($module, $updatecol)=="int" || getColumnType($module, $updatecol)=="tinyint"){
								$updatecolVal=$arr_tbl_data->$updatecol;
							} else {
								$updatecolVal="'".$arr_tbl_data->$updatecol."'";
							}
							$findRecordQuery="select * from ".$module." where ".$updatecol."= ".$updatecolVal;
							$findRecord=$db->get_row($findRecordQuery); // query to check for existance of row
							if($findRecord){
								$tempQueryStr='';
								foreach($arr_tbl_data as $key=>$value){
									if($key!==$updatecol){
									if($tempQueryStr==""){
										if(getColumnType($module, $key)=="bigint" || getColumnType($module, $key)=="int" || getColumnType($module, $key)=="tinyint"){
											$tempQueryStr.= $key."=".$value;
										} else {
											$tempQueryStr.= $key."='".$value."'";
										}
									}else {
										if(getColumnType($module, $key)=="bigint" || getColumnType($module, $key)=="int" || getColumnType($module, $key)=="tinyint"){
											$tempQueryStr.= ", ".$key."=".$value;
										}else{
											$tempQueryStr.= ", ".$key."='".$value."'";
										}
									}
									}
								}
								$updateQuery= "update ".$module." set ".$tempQueryStr." where ".$updatecol."= ".$updatecolVal;
								if($update = $db->query($updateQuery)){
									$output['success']='Data updated successfully at line '.__LINE__;
								}else{
									$output['error']='Sorry, error occured while update!';
								}
							}	else{
								//insert data in table
								$tempKeyQueryStr=''; $tempValueQueryStr='';
								foreach($arr_tbl_data as $key=>$value){
									if($tempKeyQueryStr==""){
										if(getColumnType($module, $key)=="bigint" || getColumnType($module, $key)=="int" || getColumnType($module, $key)=="tinyint"){
											$tempKeyQueryStr.= $key;
											$tempValueQueryStr.= $value;
										} else {
											$tempKeyQueryStr.= $key;
											$tempValueQueryStr.= "'".$value."'";
										}
									}else {
										if(getColumnType($module, $key)=="bigint" || getColumnType($module, $key)=="int" || getColumnType($module, $key)=="tinyint"){
											$tempKeyQueryStr.= ", ".$key;
											$tempValueQueryStr.= ", ".$value;
										}else{
											$tempKeyQueryStr.= ", ".$key;
											$tempValueQueryStr.= ", '".$value."'";
										}
									}
								}
								//draw insert query
								$insertQuery= "INSERT INTO ".$module." (".$tempKeyQueryStr.") VALUES(".$tempValueQueryStr.")";
								if($insertQueryResult = $db->query($insertQuery)){
									$output['success']='Data inserted successfully at line '.__LINE__;
								} else{
									$output['error']='Sorry, error occured while insert!';
								}
							}
						}else{
							$output['error']='Pass the unique field name';	
						}
 				}	else{
					$output['error']='No data retrieved from server at line '.__LINE__;	
				}
			}else{
				$output['error']='No data retrieved from server at line '.__LINE__;	
			}
		}	else{
			$output['error']="Please pass the action!";
		}
	}	else	{
		$output['error']="Please pass the module!";
	}
}else{
	$output['error']="Sorry, you are not authenticated user!";
}
//print_r($output);
utf8_encode_deep($output);
echo json_encode($output);
?>