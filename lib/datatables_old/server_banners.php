<?php
require_once("lib.inc.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('pictures.ID','sites.Name as SiteName','pictures.Name','pictures.Code', 'pictures.Picture', 'pictures.Type', 'pictures.Modified','pictures.Status', 'pictures.GUID');
	 
	$aColumns1 = array('ID', 'SiteName', 'Name', 'Code', 'Picture', 'Type', 'Modified', 'Status', 'GUID');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pictures.GUID";
	
	/* DB table to use */
	$sTable = "pictures left outer join sites on pictures.SiteID=sites.ID";
	
	
	
	
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
		$sWhere = "WHERE pictures.SiteID in ('".$_SESSION['site_id']."')";
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
			if ($aColumns1[$i] == "GUID") {
			
			$cats='';
			
			$sql_pic_cat=mysql_query("select * from  picturecategories where Picture_GUID='".$aRow[ $aColumns1[$i] ]."'");
				while($res_pic_cat=mysql_fetch_assoc($sql_pic_cat)){
				
					$sql_cat=mysql_query("select Name from categories where GUID='".$res_pic_cat['Category_GUID']."'");
					$res_cat=mysql_fetch_assoc($sql_cat);
					if($cats==''){
						$cats=$res_cat['Name'];
					}
					else{
						$cats.=', '.$res_cat['Name'];
					}
				}
					
				$row[] =$cats;
			
				$row[] = $aRow[ $aColumns1[$i] ];
				if($aRow["Picture"]!='' && $aRow["Type"]!='') {
				$row[] = '<a target="_blank" href="download_image.php?GUID='.$aRow[ $aColumns1[$i] ].'" title="Download Image" ><i class="splashy-download"></i><a>';
				}
				else{
				$row[] = '';
				}
				
if($user_access_level>1) {
				$row[] = '<a href="banner.php?GUID='.$aRow[ $aColumns1[$i] ].'" title="Edit this banner" ><i class="splashy-pencil"></i><a>';
				$row[]= '<a href="delete-banner.php?GUID='.$aRow[ $aColumns1[$i] ].'" title="Delete this banner" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
				}
		
				
            }
			elseif ($aColumns1[$i] == "Picture") {
				$pic= base64_encode($aRow[ $aColumns1[$i] ]);
			
			}
			elseif ($aColumns1[$i] == "Type") {
				$pic= "data:".$aRow[ $aColumns1[$i] ].";base64,".$pic;
				$row[] = '<img src="'.$pic.'" width="50" height="50" />';			
			}
			elseif ( $aColumns1[$i] == "Modified") {
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
            }

			elseif ($aColumns1[$i] == "Status") {
				if($aRow[ $aColumns1[$i] ] != 1 ){
					$row[] = "Inactive";
				}else{
					$row[] = "Active";
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