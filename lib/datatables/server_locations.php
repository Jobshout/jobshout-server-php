<?php
require_once("lib.inc.php");
$debugBool=false;
	
function findTermsArr($termStr){
	$termLenthNum= strlen($termStr);
	$termsArr=array();
	$termsArr[]=$termStr;
	$tempSubTermStr=$termStr;
	$y=$termLenthNum;
		
	while($y >= 4) {
		$tempSubTermStr=substr($tempSubTermStr, 0, -1);
		$tempSubTermStr= trim($tempSubTermStr);
		if(strlen($tempSubTermStr)>=4){
			$termsArr[]= $tempSubTermStr;
    	}
   		 $y--;
	}	
	return implode(",",$termsArr);
}

function findSubStrings($termStr, $pos=3){
	$returnedTermsStr="";
	$termLenthNum= strlen($termStr);
	if($termLenthNum>=$pos){
		$subStr= str_replace(substr($termStr, 0, $pos), substr($termStr, 0, $pos)." ", $termStr);
		if($subStr!=""){
			if($returnedTermsStr!=""){
				$returnedTermsStr.=",".findTermsArr($subStr);
			}else{
				$returnedTermsStr.=findTermsArr($subStr);
			}
		}
	}
	return $returnedTermsStr;
}

function checkAllPossiblePostcodes($termStr, $debugBool=false){
	$whereClause="";
	$termLenthNum= strlen($termStr);
	if($termLenthNum>=4){
		
		$returnedTermsStr="";
		$returnedTermsStr.=findTermsArr($termStr);
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		$substringWithoutSpace= str_replace(' ', '', $termStr);
		if($substringWithoutSpace!=""){
			if($returnedTermsStr!=""){
				$returnedTermsStr.=",".findTermsArr($substringWithoutSpace);
			}else{
				$returnedTermsStr.= findTermsArr($substringWithoutSpace);
			}	
		}
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		if($returnedTermsStr!=""){
			$returnedTermsStr.=",".findSubStrings($substringWithoutSpace,3);
		}else{
			$returnedTermsStr.=findSubStrings($substringWithoutSpace,3);
		}
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		if($returnedTermsStr!=""){
			$returnedTermsStr.=",".findSubStrings($substringWithoutSpace,4);
		}else{
			$returnedTermsStr.=findSubStrings($substringWithoutSpace,4);
		}
		
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		$termsArr=explode(",",$returnedTermsStr);
		$termsArr= array_unique($termsArr);
		
		if(count($termsArr)>0){
			$arrNum=0;
				foreach($termsArr as $row){
					$arrNum++;
					if($arrNum==0){
						$whereClause.=" or postcode LIKE '".$row."%' ";
					}else{
						$whereClause.=" or postcode LIKE '".$row."%' ";
					}
				}
		}else{
			$whereClause="or postcode LIKE '".$termStr."%'";
		}
	}
	return $whereClause;
}

	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	$set_session= set_session_values('uk_towns_cities',$sSearch,$iDisplayStart,$iDisplayLength);
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'name', 'type', 'county', 'country', 'lat', 'lng', 'GUID');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "GUID";
	
	/* DB table to use */
	$sTable = "uk_towns_cities";
	
	
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	 
		$sWhere = "WHERE 1";

	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere .= " and (";
		$sWhere .= "lat LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR lng LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR type LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR country LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR name LIKE '".mysql_real_escape_string( $_GET['sSearch'] )."%' OR postcode LIKE '".mysql_real_escape_string( $_GET['sSearch'] )."%' OR county LIKE '".mysql_real_escape_string( $_GET['sSearch'] )."%'";
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	
	$sQuery = "
		SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	if($debugBool){	
		echo $sQuery."<br>";
	}
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable $sWhere
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $iFilteredTotal==0){
		$whereClause= checkAllPossiblePostcodes($_GET['sSearch']);
		
		$subQuery="SELECT count(*) FROM uk_towns_cities Where name LIKE '".$_GET['sSearch']."%' ".$whereClause." $sOrder $sLimit";
		
		$rResultFilterTotal = mysql_query( $subQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
		
		
		if($iFilteredTotal>=1){
			$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable Where name LIKE '".$_GET['sSearch']."%' ".$whereClause." $sOrder $sLimit ";
			if($debugBool){
				echo $sQuery."<br>";
				echo $iFilteredTotal."<br>";
			}
			$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		}else if($iFilteredTotal==0){
			$termStr=substr($termStr, 0, 3);
			$resultDataQry="SELECT * FROM uk_towns_cities Where name LIKE '".$_GET['sSearch']."%' or postcode LIKE '".$_GET['sSearch']."%' $sOrder $sLimit";
			
			$rResultFilterTotal = mysql_query( $resultDataQry, $gaSql['link'] ) or die(mysql_error());
			$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
			$iFilteredTotal = $aResultFilterTotal[0];
			
			if($iFilteredTotal>=1){
				$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable Where name LIKE '".$_GET['sSearch']."%' or postcode LIKE '".$_GET['sSearch']."%' $sOrder $sLimit ";
				if($debugBool){
					echo $sQuery."<br>";
					echo $iFilteredTotal."<br>";
				}
				$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
			}
		}
	}
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	if($debugBool){	exit;	}
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();

		/* Add the  details image at the start of the display array */
		//$row[] = '<img src="img/details_open.png">';

		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ($aColumns[$i] == "GUID") {
				$row[] = $aRow[ $aColumns[$i] ];
				
if($user_access_level>1) {
				$row[] = '<a href="location.php?GUID='.$aRow[ $aColumns[$i] ].'" title="Edit this location" ><i class="splashy-pencil"></i><a>';
				$row[]= '<a href="delete-location.php?GUID='.$aRow[ $aColumns[$i] ].'" title="Delete this location" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
				}
						
            }
			else
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>