<?php

require_once("lib.inc.php");


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('documents.ID','sites.Name', 'sites.WebsiteAddress', 'documents.Document','documents.Code','documents.Body','documents.PageTitle', 'documents.Modified', 'documents.Published_timestamp','documents.short_url','documents.Status','documents.GUID');
	
	$aColumns1 = array('WebsiteAddress', 'Code', 'ID', 'Name', 'Document', 'PageTitle', 'Modified', 'Published_timestamp', 'short_url', 'Status', 'GUID');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "documents.GUID";
	
	/* DB table to use */
	$sTable = "documents left outer join sites on documents.SiteID=sites.ID";
	
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
	 if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 {
		$sWhere = "WHERE documents.SiteID in ('".$_SESSION['site_id']."') and documents.Type='page'";
	}
	else
	{
		$sWhere = "WHERE documents.Type='page'";
	}
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere .= " and (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
			elseif ( $aColumns1[$i] == "ID" )
			{
				if($curr_web!=''){
				$row[]=$aRow[ $aColumns1[$i] ].'<a  href="'.$curr_web.'/content.php?code='.$curr_code.'" class="page_code" ><a>';
				}
				else{
				$row[]=$aRow[ $aColumns1[$i] ].'<a href="" class="page_code" ><a>';
				}
			}
			elseif ( $aColumns1[$i] == "GUID" )
			{
				/* Special output formatting for 'version' column */
				$row[]=$aRow[ $aColumns1[$i] ];
				$curr_id=$aRow[ $aColumns1[$i] ];
			}
			elseif ($aColumns1[$i] == "Published_timestamp" || $aColumns1[$i] == "Modified") {
				$row[] = Date('d M Y', $aRow[ $aColumns1[$i] ] ).", ".Date('h:i A', $aRow[ $aColumns1[$i] ] );
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }elseif ($aColumns1[$i] == "Status") {
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
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }
			else
			{
				/* General output */
				$row[] = $aRow[ $aColumns1[$i] ];
			}
		}

if($user_access_level>1) {
		$row[] = '<a href="page.php?GUID='.$curr_id.'" title="Edit this page" ><i class="splashy-pencil"></i><a>';
		$row[]= '<a href="delete-page.php?GUID='.$curr_id.'" title="Delete this page" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-document_a4_remove"></i><a>';
		}
		else
		{
			//$row[] = '<a href="javascript:void(0)" title="Not allowed" ><i class="splashy-pencil"></i><a>';
		//$row[]= '<a href="javascript:void(0)" title="Not allowed" ><i class="splashy-document_a4_remove"></i><a>';
		}
		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>