<?php 
session_start();

require_once("connect.php");

$sWhere = " 1 ";
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
{
	$sWhere = " SiteID in ('".$_SESSION['site_id']."') ";
}
elseif(isset($_GET['site_id']) && $_GET['site_id']!='')
{
	$sWhere = " SiteID in (".$_GET['site_id'].") ";
}

$start_date=strtotime($_GET['s_date']);
$end_date=strtotime($_GET['e_date']);
$s_date=$start_date;

$curr_year=date('Y',$start_date);
$curr_month=date('m',$start_date);
$curr_day=date('d',$start_date);
$i=0;

if($_GET['mode']=='year') {
	while($s_date<$end_date){
		$s_date=strtotime($curr_month.'/'.$curr_day.'/'.$curr_year);
		$next_month=$curr_month+1;
		$next_year=$curr_year;
		if($curr_month==12){
			$curr_month=0;
			$curr_year=$curr_year+1;
			$next_month=1;
			$next_year=$curr_year+1;
		}
		$e_date=strtotime($next_month.'/1/'.$next_year);
		if($e_date>$end_date){
			$e_date=$end_date;
		}
				
		$result[$i]['date_time']=date('m/d/Y',$e_date);
		
		$active_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status=1");
		
		$result[$i]['active_jobs']=$active_jobs;
		
		$inactive_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status<>1");
		
		$result[$i]['inactive_jobs']=$inactive_jobs;
		
		$i++;
		$curr_day=1;	
		$curr_month++;
		$s_date=$e_date;
	}
}
elseif($_GET['mode']=='month'){
		while($s_date<$end_date){
				
		$e_date=strtotime(date('m/d/Y',$s_date) . ' +4 day');
		if($e_date>$end_date){
			$e_date=$end_date;
		}
				
		$result[$i]['date_time']=date('m/d/Y',$e_date);
		
		$active_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status=1");
		
		$result[$i]['active_jobs']=$active_jobs;
		
		$inactive_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status<>1");
		
		$result[$i]['inactive_jobs']=$inactive_jobs;
		
		$i++;
		$curr_day=1;	
		$curr_month++;
		$s_date=$e_date;
	}

}
elseif($_GET['mode']=='week'){
	while($s_date<$end_date){
				
		$e_date=strtotime(date('m/d/Y',$s_date) . ' +1 day');
		if($e_date>$end_date){
			$e_date=$end_date;
		}
				
		$result[$i]['date_time']=date('m/d/Y',$e_date);
		
		$active_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status=1");
		
		$result[$i]['active_jobs']=$active_jobs;
		
		$inactive_jobs = $db->get_var("SELECT count( * ) as num FROM `documents` WHERE $sWhere and Type = 'job' and PostedTimestamp between ".$s_date." and ".$e_date." and Status<>1");
		
		$result[$i]['inactive_jobs']=$inactive_jobs;
		
		$i++;
		$curr_day=1;	
		$curr_month++;
		$s_date=$e_date;
	}

}
echo json_encode($result);
?>