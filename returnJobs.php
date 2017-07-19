<?php
$conn = new Mongo( 'mongodb://85.92.89.214:27017/' );
$mon_db= $conn->cvscreen;
$collection = $mon_db->documents;
$cond=array();
$cond[]=array('Type' => 'job');
$cond[]=array('SiteID' => 2970);
$start=$_GET['start'];
$limit=$_GET['limit'];

if(isset($_GET['keyword']) && $_REQUEST['keyword']<>''){
	$key = $_GET['keyword'];
	$cond[]	=array('$or' => array( array("Document" => new MongoRegex("/".$key."/")),array("Body" => new MongoRegex("/".$key."/")),array("Code" => new MongoRegex("/".$key."/")) ));
	
}
// echo json_encode($cond);
$totalrecords= $collection->find(array('$and' => $cond ))->sort(array("ID" => -1));
$total_pages = $totalrecords->count();
if($total_pages!=0){
	
	$output = array(
		"iTotalRecords" => $total_pages,
		"aaData" => array()
	);
	$records= $collection->find(array('$and' => $cond ))->sort(array("ID" => -1))->limit($limit)->skip($start);
	//var_dump($records->getNext());
	if($records->count() !=0){
		foreach($records as $record){ 
			for ( $i=0 ; $i<count($total_pages) ; $i++ ){
				$row = array();
				$row['ID']=$record['ID'];
				$row['Reference']=$record['Reference'];
				$row['Document']=$record['Document'];
				$row['PageTitle']=substr($record['PageTitle'],0,50)."..";
				$row['Modified']=date('d M Y',$record['Modified']).','.date('H:i:s',$record['Modified']);
				$row['PostedTimestamp']=date('d M Y',$record['PostedTimestamp']).','.date('H:i:s',$record['PostedTimestamp']);
				if($record['Status'] != 1){
					$row['Status']=  "Inactive";
				} else { 
					$row['Status']= "Active"; 
				}
				$row['GUID']=$record['GUID'];
			} 
			$output['aaData'][] = $row;
		}
	}
}else{
	$output='No record found';
}
	echo json_encode( $output );
?>
