<?php
require_once("lib.inc.php");
	
	//set session values
	$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$iDisplayLength = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 25;
	$sSearch = isset($_GET['sSearch']) ? $_GET['sSearch'] : '';
	
	if( isset($_GET['repeatQuery']) && $_GET['repeatQuery'] == "yes" ){
		// dnt change this
	}else{
		$set_session= set_session_values('jobapplications',$sSearch,$iDisplayStart,$iDisplayLength);
	}
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('jobapplications.ID','sites.Name as SiteName','jobapplications.Name','jobapplications.Email','jobapplications.TelephoneMobile','jobapplications.Modified','jobapplications.GUID','jobapplications.CVFileName', 'jobapplications.CV_File_Content');
	 
	$aColumns1 = array('ID','SiteName','Name','Email','TelephoneMobile','Modified','GUID','CVFileName', 'CV_File_Content');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "jobapplications.GUID";
	
	/* DB table to use */
	if(isset($_GET['job_guid']) && $_GET['job_guid']!='') { 
		$sTable = "jobapplications left outer join sites on jobapplications.SiteID=sites.ID left outer join jobsapplicants on jobapplications.GUID=jobsapplicants.JobApplication_GUID";
	}
	else{	
		$sTable = "jobapplications left outer join sites on jobapplications.SiteID=sites.ID";
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
		$sWhere = "WHERE jobapplications.SiteID in ('".$_SESSION['site_id']."')";
	}
	else
	{
		$sWhere = "where 1";
	}
	if(isset($_GET['job_guid']) && $_GET['job_guid']!='') { 
	 	$sWhere .= " and (jobsapplicants.Job_GUID='".$_GET['job_guid']."')";
	}
	
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere .= " and (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$srch_col=$aColumns[$i];
			if($srch_col=='sites.Name as SiteName') {
				$srch_col='sites.Name';
			}else if($srch_col=="jobapplications.CV_File_Content"){
				
			}
			$sWhere .= $srch_col." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		
		// to apply free text search on CV_File_Content
		$sWhere .= " or ";
		$keyArrStr = explode(' ',$_GET['sSearch']);
		$keyArrStr = array_unique($keyArrStr);

        $findStr="";
        $totalSearchTagsNum=count($keyArrStr);
        for($n=0;$n<$totalSearchTagsNum;$n++){
        	if($findStr!=""){
                $findStr.=' +"'.$keyArrStr[$n].'"';
            }else{
                $findStr.='+"'.$keyArrStr[$n].'"';
            }
        }
        $sWhere .= " MATCH(jobapplications.CV_File_Content) AGAINST('".$findStr."' IN BOOLEAN MODE) ";
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
	$latitude=''; $longitude="";
	
	if(isset($_REQUEST['location']) && $_REQUEST['location']<>'' && $_REQUEST['location']<>'null'){
        $loc_str=addslashes($_REQUEST['location']);
        if(isset($_REQUEST['radius']) && $_REQUEST['radius']<>'' && $_REQUEST['radius']<>'0'){
        	$radius=$_REQUEST['radius'];
        	

            $subQuery="SELECT * FROM uk_towns_cities Where postcode='".$_REQUEST['location']."' ";
			$executeSubQuery = mysql_query( $subQuery, $gaSql['link'] ) or die(mysql_error());
			if(mysql_num_rows($executeSubQuery)!=0){
				$query_row = mysql_fetch_array($executeSubQuery);
			
            	$latitude=$query_row['lat'];
                $longitude=$query_row['lng'];
        	}
        	if(isset($latitude) && $latitude!="" && isset($longitude) && $longitude!=""){
        		$findAllPostcodeQuery = "SELECT postcode, ( 3959 * acos( cos( radians(".$latitude.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians( lat ) ) ) ) AS distance FROM uk_towns_cities WHERE postcode<>'' HAVING distance < $radius";
                $executePostcodeQuery = mysql_query( $findAllPostcodeQuery, $gaSql['link'] ) or die(mysql_error());
				$inClauseStr="";
				if(mysql_num_rows($executePostcodeQuery)!=0) {
    				while($rowData = mysql_fetch_array($executePostcodeQuery)) {
    					if($inClauseStr!=""){
    						$inClauseStr.= ", '".$rowData['postcode']."'";
    					}else{
    						$inClauseStr.= "'".$rowData['postcode']."'";
    					}
    				}
    			}
				$sWhere .= " and (jobapplications.HomePostcode IN (".$inClauseStr."))";                   
        	}
        }	else	{
        	$sWhere .= " and (jobapplications.HomePostcode='".$loc_str."')";
        }
    }
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	 $sQuery="";
	if( isset($_GET['repeatQuery']) && $_GET['repeatQuery'] == "yes" ){
		$tempTotalRecords=0;
		
		if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications_query']) && isset($_SESSION['last_search']['jobapplications_query']['total_records'])) { 
			$tempTotalRecords=$_SESSION['last_search']['jobapplications_query']['total_records'];
		}
		if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications_query']) && isset($_SESSION['last_search']['jobapplications_query']['sQuery'])) { 
			$sQuery=  $_SESSION['last_search']['jobapplications_query']['sQuery'];
			set_session_query('jobapplications_query',$sQuery, $tempTotalRecords, $iDisplayStart, $iDisplayLength);
			$sQuery.=" LIMIT ".$iDisplayStart.",".$iDisplayLength;
		}
	}
	
	$mainQuery="";
	
	if($sQuery==""){
		$mainQuery="
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
		";
		$sQuery = $mainQuery."
			$sLimit
		";
	}
	//echo $sQuery; exit;
	
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
	
	if($mainQuery!=""){
		set_session_query('jobapplications_query',$mainQuery, $iFilteredTotal, $iDisplayStart, $iDisplayLength);
	}
	/*
	 * Output
	 */
	 $sEcho= 0;
	if(isset($_GET['sEcho']) && $_GET['sEcho']!=""){
	 	$sEcho=$_GET['sEcho'];
	}
	$output = array(
		"Echo" => intval($sEcho),
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
            }
            elseif ($aColumns1[$i] == "CVFileName") {
				if($aRow[ $aColumns1[$i] ]!='') {
					$row[] = '<a target="_blank" href="download_cv.php?GUID='.$curr_id.'" title="Download CV" ><i class="splashy-document_letter"></i> '.$aRow[ $aColumns1[$i] ].'<a>';
				}
				else {
					$row[] = '';
				}
            }
			
			 elseif ($aColumns1[$i] == "CV_File_Content") {
                          if($aRow[ $aColumns1[$i] ]!="") {
                          	if(strlen($aRow[ $aColumns1[$i] ])>250){
								$tempStr=substr($aRow[ $aColumns1[$i] ],0,250)."...";
							}else{
								$tempStr= $aRow[ $aColumns1[$i] ];
							}
                            
                            if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
                              {
                               
                                $keyArrStr = explode(' ',$_GET['sSearch']);
								$keyArrStr = array_unique($keyArrStr);

        						for($n=0;$n<count($keyArrStr);$n++){
        							$searchStr=$keyArrStr[$n];
        							$replaceStr='<span CLASS="highlighttxt" >'.$keyArrStr[$n].'</span>';
                                	$tempStr=str_replace($searchStr,$replaceStr,$tempStr);
								}

                              }
                             //$tempStr= wordwrap($tempStr,50,"<br>\n");
                            $row[] = $tempStr;
                          }
                          else {
                            $row[] = '';
                          }
                        }
			else
			{
				/* General output */
				 $tempStr=$aRow[ $aColumns1[$i] ];
                          if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
                            {
                            	$keyArrStr = explode(' ',$_GET['sSearch']);
								$keyArrStr = array_unique($keyArrStr);

        						for($n=0;$n<count($keyArrStr);$n++){
        							$searchStr=$keyArrStr[$n];
        							$replaceStr='<span CLASS="highlighttxt" >'.$keyArrStr[$n].'</span>';
                                	$tempStr=str_replace($searchStr,$replaceStr,$tempStr);
								}
							}
						$row[] = iconv("UTF-8", "ISO-8859-1//IGNORE",$tempStr);
			}
		}
		if($user_access_level>1) {
			$row[] = '<input type="hidden" class="list_guids" value="'.$curr_id.'"><a href="jobapp.php?GUID='.$curr_id.'" title="Edit this job application" ><i class="splashy-pencil"></i></a>';
			$row[]= '<a href="delete-jobapp.php?GUID='.$curr_id.'" title="Delete this job application" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i></a>';
		}
		

		$row['extra'] = 'hrmll';
		$output['aaData'][] = $row;
	}
	//print_r($output);
	
	echo json_encode( $output );
?>