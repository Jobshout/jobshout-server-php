<?php
require_once("lib.inc.php");

	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	$set_session= set_session_values('pdf_docs',$sSearch,$iDisplayStart,$iDisplayLength);
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 $aColumns = array('pdf_docs.id','sites.Name as SiteName','pdf_docs.doc_title','pdf_docs.modified','pdf_docs.status', 'pdf_docs.uuid', 'pdf_docs.doc_name');
	 
	$aColumns1 = array('id','SiteName','doc_title','modified','status','uuid','doc_name');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pdf_docs.uuid";
	
	/* DB table to use */
	$sTable = "pdf_docs left outer join sites on pdf_docs.SiteID=sites.ID";
	
	
	
	
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
		$sWhere = "WHERE pdf_docs.SiteID in ('".$_SESSION['site_id']."')";
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
			if ( $aColumns1[$i] == "id" )
			{
				/* Special output formatting for 'version' column */
				$row[]=$aRow[ $aColumns1[$i] ];
				$pdf_id=$aRow[ $aColumns1[$i] ];
			}
			elseif ( $aColumns1[$i] == "uuid" )
			{
				/* Special output formatting for 'version' column */
				$row[]=$aRow[ $aColumns1[$i] ];
				$curr_id=$aRow[ $aColumns1[$i] ];
			}
			elseif ( $aColumns1[$i] == "doc_title" )
			{
				$row[] = $aRow[ $aColumns1[$i] ];
				$docs='';
				
				 if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 {
		
		$sql_name=mysql_query("SELECT document_id FROM pdf_to_documents WHERE siteid in ('".$_SESSION['site_id']."') and pdf_id='$pdf_id'");
	}
	else
	{
		$sql_name=mysql_query("SELECT document_id FROM pdf_to_documents WHERE pdf_id='$pdf_id'");
	}
	
				
				while($res_name=mysql_fetch_assoc($sql_name)){
					$sql=mysql_query("select document from documents where id=".$res_name['document_id']."");
					$res=mysql_fetch_assoc($sql);
					if($docs==''){
						$docs=$res['document'];
					}
					else{
						$docs.=", ".$res['document'];
					}
				}
				
				$row[] = $docs;
				
			}
			elseif ($aColumns1[$i] == "modified" ) {
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
			elseif ($aColumns1[$i] == "status") {
				if($aRow[ $aColumns1[$i] ] != 1 ){
					$row[] = "Inactive";
				}else{
					$row[] = "Active";
				}
            }
			
			elseif ($aColumns1[$i] == "doc_name") {
				if($aRow[ $aColumns1[$i] ]!='') {
					$row[] = '<a target="_blank" href="download_pdf_file.php?GUID='.$curr_id.'" title="Download File" ><i class="splashy-document_letter"></i>'.$aRow[ $aColumns1[$i] ].'<a>';
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
		
		$row[] = '<a href="pdf_doc.php?GUID='.$curr_id.'" title="Edit this file" ><i class="splashy-pencil"></i><a>';
		$row[]= '<a href="delete-pdfdoc.php?GUID='.$curr_id.'" title="Delete this file" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a>';
		}
		

		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	//print_r($output);
	
	echo json_encode( $output );
?>