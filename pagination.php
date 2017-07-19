<?php
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	$limit=$_GET['limit'];
	$total_pages=$_GET['total_pages'];
	$keyword='';
	if(isset($_GET['page']))					//how many items to show per page
		$page = $_GET['page'];
	else
		$page = 0;	
	if($page>0) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
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
			$pagination.= "<li><a href=\"javascript:void(0)\" class=\"prev\" onclick=\"get_pagination($counter)\"> < Previous</a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"javascript:void(0)\" class=\"prev\" onclick=\"get_data(".$start.','.$limit.','.$page.")\"> < Previous</a></li>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><a href=\"javascript:void(0)\" class=\"selected\" onclick=\"get_pagination($counter)\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">$counter</a></li>";					
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
						$pagination.= "<li><a href=\"javascript:void(0)\" class=\"selected\" onclick=\"get_pagination($counter)\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">1</a></li>";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_pagination($counter)\">2</a></li>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><a href=\"javascript:void(0)\" class=\"selected\" onclick=\"get_pagination($counter)\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">1</a></li>";
				$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">2</a></li>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><a href=\"javascript:void(0)\" class=\"selected\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">$counter</a></li>;";
					else
						$pagination.= "<li><a href=\"javascript:void(0)\" onclick=\"get_data(".$start.','.$limit.','.$page.")\">$counter</a></li>;";					
				}
			}


		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"javascript:void(0)\" class=\"next\">Next ></a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"javascript:void(0)\" class=\"next\">Next > </a></li>";
		$pagination.= "</ul></div><div class=\"clearfix\"></div></div>";		
	}
	echo $pagination;
?>
    
			<script>
				var keyword='',start,limit;
				function get_data(start,limit,page){
					//var keyword= document.getElementById('srch').value;
					var dataString = 'start='+start+'&limit='+limit;
					jsonRow = 'returnJobs.php?'+dataString;
					$.getJSON(jsonRow,function(result){
						var html;
						$.each(result, function(i,item){
							html +='<tr>';
							html += '<td>'+item.ID+'</td>';
							html += '<td>'+item.Reference+'</td>';
							html += '<td>'+item.Document+'</td>';
							html += '<td>'+item.PageTitle+'</td>';
							html += '<td>'+item.Modified+'</td>';
							html += '<td>'+item.PostedTimestamp+'</td>';
							html += '<td>'+item.Status+'</td>';
							html += '<td><a href="#.php?GUID='+item.GUID+'title="Edit this job" ><i class="splashy-pencil"></i><a></td>';
							html += '<td><a href="#?GUID='+item.GUID+'title="Delete this job" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a></td>';
							html += '</tr>';
						});
						$('.data').html(html);
					});
				}
				function get_pagination(limit){
					alert(limit)
				}
			</script>