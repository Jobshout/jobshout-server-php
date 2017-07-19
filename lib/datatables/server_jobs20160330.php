<?php
require_once("lib.inc.php");

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('documents.ID','sites.Name', 'documents.Reference', 'documents.Document','documents.PageTitle', "CONCAT( wi_users.firstname, ' ', wi_users.lastname ) as UserName",'documents.GUID', 'documents.Modified','documents.PostedTimestamp','documents.Status', 'sites.WebsiteAddress', 'documents.Code','documents.Body');
	
	$aColumns1 = array('WebsiteAddress', 'Code', 'ID', 'Name', 'Reference', 'Document', 'PageTitle','UserName', 'GUID', 'Modified', 'PostedTimestamp', 'Status');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "documents.GUID";
	
	/* DB table to use */
	
	if(isset($_GET['cat_id']) && $_GET['cat_id']!='' && isset($_GET['cat_guid']) && $_GET['cat_guid']!='') { 
		$sTable = "documents left outer join sites on documents.SiteID=sites.ID left outer join wi_users on documents.UserID=wi_users.ID join documentcategories on (documents.GUID=documentcategories.Document_GUID)";
	}
	else{	
		$sTable = "documents left outer join sites on documents.SiteID=sites.ID left outer join wi_users on documents.UserID=wi_users.ID"; 
	 }
	
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
				$sort_col=$aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
				if($sort_col=='sites.Name as SiteName') {
					$sort_col='sites.Name';
				}
				elseif($sort_col=="CONCAT( wi_users.firstname, ' ', wi_users.lastname ) as UserName") {
					$sort_col='wi_users.firstname';
				}
				$sOrder .= $sort_col."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	$set_session= set_session_values('jobs',$sSearch,$iDisplayStart,$iDisplayLength);
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	 if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 {
		$sWhere = "WHERE documents.SiteID in ('".$_SESSION['site_id']."') and documents.Type='job'";
	}
	else
	{
		$sWhere = "WHERE documents.Type='job'";
	}
	if(isset($_GET['cat_id']) && $_GET['cat_id']!='' && isset($_GET['cat_guid']) && $_GET['cat_guid']!='') { 
	 	$sWhere .= "and (documentcategories.CategoryID='".$_GET['cat_id']."')";
	 	// $sWhere .= "and (documentcategories.Category_GUID='".$_GET['cat_guid']."')";
	 }
	if(isset($_GET['status'])){
		
		if($_GET['status']=='true') { 
			// $sWhere .= "and (documents.Status=)";
		}else{
			$sWhere .= "and (documents.Status=1)";
		}
	}
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere .= " and (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$srch_col=$aColumns[$i];
			if($srch_col=='sites.Name as SiteName') {
				$srch_col='sites.Name';
			}
			elseif($srch_col=="CONCAT( wi_users.firstname, ' ', wi_users.lastname ) as UserName") {
				$srch_col='wi_users.firstname';
			}
			$sWhere .= $srch_col." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
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
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	// echo $sQuery;
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"Echo" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();

		/* Add the  details image at the start of the display array */
		//$row[] = '<img src="img/details_open.png">';
		$curr_id='';
		$curr_code='';
		$curr_web='';
		for ( $i=0 ; $i<count($aColumns1) ; $i++ )
		{
			if ( $aColumns1[$i] == "WebsiteAddress" )
			{
				$curr_web=$aRow[ $aColumns1[$i] ];
			}
			elseif ( $aColumns1[$i] == "Code" )
			{
				$curr_code=$aRow[ $aColumns1[$i] ];
			}
			elseif ( $aColumns1[$i] == "GUID" )
			{
				/* Special output formatting for 'version' column */
				$row[]=$aRow[ $aColumns1[$i] ];
				$curr_id=$aRow[ $aColumns1[$i] ];
				
				$count_applications = "SELECT COUNT(*) FROM jobsapplicants where Job_GUID='".$curr_id."' ";
				$aresult = mysql_query( $count_applications, $gaSql['link'] ) or die(mysql_error());
				$aTotal = mysql_fetch_array($aresult);
				if($aTotal[0]==0){
					$row[] = '';
				}else{
					$row[] = '<a  href="jobapps.php?job_guid='.$curr_id.'" class="page_code" target="_blank">'.$aTotal[0].'</a>';
				}
			}
			elseif ($aColumns1[$i] == "PostedTimestamp") {
				$row[] = Date('d M Y', $aRow[ $aColumns1[$i] ] ).", ".Date('h:i A', $aRow[ $aColumns1[$i] ] );;
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }
			elseif ($aColumns1[$i] == "Modified") {
				$date = date('d M Y',$aRow[ $aColumns1[$i] ]);
				$time = date('H:i:s',$aRow[ $aColumns1[$i] ]);
				$time_arr=explode(":",$time);
				if($time_arr[0]>12){
					$hour=$time_arr[0]-12;
					$am_pm="PM";
				}
				else{
					$hour=$time_arr[0];
					$am_pm="AM";
				}
				$time_string=$hour.":".$time_arr[1]." ".$am_pm;
				$row[] = $date.','.$time_string;
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }
			elseif ($aColumns1[$i] == "Status") {
				if($aRow[ $aColumns1[$i] ] != 1 ){
					$row[] = "Inactive";
				}else{
					$row[] = "Active";
				}
            }
			elseif ($aColumns1[$i] == "PageTitle") {
				if(strlen($aRow[ $aColumns1[$i] ])>30)
				{
				$row[] = substr($aRow[ $aColumns1[$i] ],0,30)."...";
				}
				else
				{
				$row[] = $aRow[ $aColumns1[$i] ];
				}
				//echo Date('m-d-Y', $aRow[ $aColumns1[$i] ] );
            }
			else
			{
				/* General output */
				$row[] = iconv("UTF-8", "ISO-8859-1//IGNORE",$aRow[ $aColumns1[$i] ]);
			}
		}
		if($curr_web!=''){
			$row[] = '<a href="'.$curr_web.'/content.php?code='.$curr_code.'" title="Preview" target="_blank"><img src="img/preview.png" alt="Preview"><a>';
		}else{
			$row[] = '';
		}
		if($user_access_level>1) {
			$row[] = '<a href="job.php?GUID='.$curr_id.'" title="Edit this job" ><i class="splashy-pencil"></i><a>';
			$row[]= '<a href="delete-job.php?GUID='.$curr_id.'" title="Delete this job" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
		}
		

		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	//print_r($output);
	
	echo json_encode( $output );
?>