<?php
require_once("lib.inc.php");
	
	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	$set_session= set_session_values('videos',$sSearch,$iDisplayStart,$iDisplayLength);
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 $aColumns = array('sites.Name as SiteName', 'videos.title', 'videos.published_timestamp', 'videos.modified', 'videos.active', 'videos.uuid');
	 
	$aColumns1 = array('SiteName', 'title', 'published_timestamp', 'modified', 'active', 'uuid');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "videos.uuid";
	
	/* DB table to use */
	$sTable = "videos left outer join sites on videos.SiteID=sites.ID";
	
	
	
	
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
		$sWhere = "WHERE SiteID in ('".$_SESSION['site_id']."')";
	}
	else
	{
		$sWhere = "WHERE 1";
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

		for ( $i=0 ; $i<count($aColumns1) ; $i++ )
		{
			if ($aColumns1[$i] == "modified" ) {
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
            }else if ($aColumns1[$i] == "published_timestamp") {
				$row[] = Date('d M Y', $aRow[ $aColumns1[$i] ] ).", ".Date('h:i A', $aRow[ $aColumns1[$i] ] );
				//echo Date('m-d-Y', $aRow[ $aColumns[$i] ] );
            }

			/*elseif ($aColumns[$i] == "categories" &&  $aRow[ $aColumns[$i]]!='') {
				
				$sql_name=mysql_query("select Name from categorygroups where ID=".$aRow[ $aColumns[$i] ]."");
				$res_name=mysql_fetch_assoc($sql_name);
				$row[] = $res_name['Name'];
            }*/

			elseif ($aColumns1[$i] == "active" ) {
				
				if($aRow[ $aColumns1[$i] ]==0){ $row[] = "Inactive"; } else { $row[] = "Active"; }
	
            }

			elseif ($aColumns1[$i] == "uuid") {
				$row[] = $aRow[ $aColumns1[$i] ];
				
if($user_access_level>1) {
				$row[] = '<a href="video.php?UUID='.$aRow[ $aColumns1[$i] ].'" title="Edit this video" ><i class="splashy-pencil"></i><a>';
				$row[]= '<a href="delete-video.php?GUID='.$aRow[ $aColumns1[$i] ].'" title="Delete this video" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
				}
		
				
            }
			else if ( $aColumns1[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns1[$i] ];
			}
		}
		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>