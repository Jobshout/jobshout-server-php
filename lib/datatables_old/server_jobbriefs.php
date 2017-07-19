<?php
require_once("lib.inc.php");

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 $aColumns = array('jobbriefs.ID','sites.Name as SiteName','jobbriefs.Name','jobbriefs.Email','jobbriefs.Mobile','jobbriefs.Modified','jobbriefs.GUID','jobbriefs.JobBriefFileName');
	$aColumns1 = array('ID','SiteName','Name','Email','Mobile','Modified','GUID','JobBriefFileName');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "jobbriefs.GUID";
	
	/* DB table to use */
	$sTable = "jobbriefs left outer join sites on jobbriefs.SiteID=sites.ID";
	
	
	
	
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
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	  if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 {
		$sWhere = "WHERE jobbriefs.SiteID in ('".$_SESSION['site_id']."')";
	}
	else
	{
		$sWhere = "where 1";
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
		for ( $i=0 ; $i<count($aColumns1) ; $i++ )
		{
			if ( $aColumns1[$i] == "GUID" )
			{
				/* Special output formatting for 'version' column */
				$row[]=$aRow[ $aColumns1[$i] ];
				$curr_id=$aRow[ $aColumns1[$i] ];
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
				//$row[] = Date('d M Y', $aRow[ $aColumns1[$i] ] );
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }
			elseif ($aColumns1[$i] == "JobBriefFileName") {
				if($aRow[ $aColumns1[$i] ]!='') {
					$row[] = '<a target="_blank" href="download_jobfile.php?GUID='.$curr_id.'" title="Download File" ><i class="splashy-document_letter"></i> '.$aRow[ $aColumns1[$i] ].'<a>';
				}
				else {
					$row[] = '';
				}
            }
			
			else
			{
				/* General output */
				$row[] = $aRow[ $aColumns1[$i] ];
			}
		}

		
		
if($user_access_level>1) {
		
		$row[] = '<a href="jobbrief.php?GUID='.$curr_id.'" title="Edit this job brief" ><i class="splashy-pencil"></i><a>';
		$row[]= '<a href="delete-jobbrief.php?GUID='.$curr_id.'" title="Delete this job brief" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
		}
		

		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	//print_r($output);
	
	echo json_encode( $output );
?>