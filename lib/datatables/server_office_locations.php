<?php
require_once("lib.inc.php");
	
	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	$set_session= set_session_values('locations_live',$sSearch,$iDisplayStart,$iDisplayLength);
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'Name', 'Created', 'Modified', 'Type', 'Active', 'ID');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "ID";
	
	/* DB table to use */
	$sTable = "locations_live";
	
	
	
	
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

		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ($aColumns[$i] == "Created" || $aColumns[$i] == "Modified") {
				$row[] = Date('d M Y', $aRow[ $aColumns[$i] ] ).", ".Date('h:i A', $aRow[ $aColumns[$i] ] );
            }elseif ($aColumns[$i] == "Active") {
            	if($aRow[ $aColumns[$i] ]==1){
            		$row[] = "Active";
            	}else{
            		$row[] = "Inactive";
            	}
				
            }elseif ($aColumns[$i] == "ID") {
				$row[] = $aRow[ $aColumns[$i] ];
				if($user_access_level>1) {
				$row[] = '<a href="office-location.php?id='.$aRow[ $aColumns[$i] ].'" title="Edit this location" ><i class="splashy-pencil"></i><a>';
				//$row[]= '<a href="delete-location.php?id='.$aRow[ $aColumns[$i] ].'" title="Delete this location" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
				}
						
            }elseif ($aColumns[$i] == "Type") {
            	if(is_string($aRow[ $aColumns[$i] ]) && is_array(json_decode($aRow[ $aColumns[$i] ], true))){
            		$jsonDecode=json_decode($aRow[ $aColumns[$i] ]);
            		$row[] =$jsonDecode;
            	}else{
            		$row[] = $aRow[ $aColumns[$i] ];
            	}
            }else{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>