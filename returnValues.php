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

// $RefererSites = $db->get_Results("SELECT distinct(RefererSite) FROM logfile where RefererSite<>'' and $sWhere");

// $db->debug();
// $i=0;
$arr_start_date=explode('/', $_GET['s_date']);
$s_date=$arr_start_date[1].'/'.$arr_start_date[0].'/'.$arr_start_date[2];
$arr_end_date=explode('/', $_GET['e_date']);
$e_date=$arr_end_date[1].'/'.$arr_end_date[0].'/'.$arr_end_date[2];
$s_date=strtotime($s_date);
$e_date=strtotime($e_date);
// if($RefererSites){
	// foreach($RefererSites as $RefererSite){
		// $referer=$RefererSite->RefererSite;
		if($query = $db->get_results("SELECT count( * ) as num, RefererSite FROM logfile where $sWhere and RefererSite <> '' and HitTimestamp between ".$s_date." and ".$e_date." GROUP BY RefererSite ORDER BY count( * ) DESC")){
			$i=0;
			foreach($query as $RefererSite){
				$referer=$RefererSite->RefererSite;
				$num= $RefererSite->num;
				if($num>0)
				{
					$result[$i]['label']=$referer;
					$result[$i]['data']=$num;
					$i++;
				}
			}
		}else{
$result=0;
}
echo json_encode($result);
?>