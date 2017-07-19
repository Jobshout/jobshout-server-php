<?php
	/*
		Place code to connect to your DB here.
	*/
	// include your code to connect to DB.

		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	
	
	/* Setup vars for query. */
 	//your file name  (the name of this file)
	 
	if(isset($_GET['page']))								//how many items to show per page
		$page = $_GET['page'];
	else
		$page = 0;	
	if($page>0) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	//$sql = "SELECT * FROM $tbl_name LIMIT $start, $limit";
	//$result = mysql_query($sql);
	
	
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;	
	$pagestart=$start+1;
	$pageend=$start+$limit;	
	if($total_pages<$pageend)
			$pageend=$total_pages;			//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div><div class=\"search-amount span6\" style=\"margin:20px 0;\">Showing $pagestart to $pageend of $total_pages entries</div><div class=\"pagination\" style=\"float:right;\"><ul style=\"box-shadow:none;\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a href=\"".$targetpage."page=$prev\" class=\"prev\"> < Previous</a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"#\" class=\"prev\" > < Previous</a></li>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><a href=\"#\" class=\"selected\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"".$targetpage."page=$counter\">$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><a href=\"#\" class=\"selected\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"".$targetpage."page=$counter\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"".$targetpage."page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"".$targetpage."page=$lastpage\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"".$targetpage."page=1\">1</a></li>";
				$pagination.= "<li><a href=\"".$targetpage."page=2\">2</a></li>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><a href=\"#\" class=\"selected\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"".$targetpage."page=$counter\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"".$targetpage."page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"".$targetpage."page=$lastpage\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"".$targetpage."page=1\">1</a></li>";
				$pagination.= "<li><a href=\"".$targetpage."page=2\">2</a></li>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><a href=\"#\" class=\"selected\">$counter</a></li>;";
					else
						$pagination.= "<li><a href=\"".$targetpage."page=$counter\">$counter</a></li>;";					
				}
			}


		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"".$targetpage."page=$next\" class=\"next\">Next ></a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"#\" class=\"next\">Next > </a></li>";
		$pagination.= "</ul></div><div class=\"clearfix\"></div></div>";		
	}
	echo $pagination;
?>