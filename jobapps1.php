<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); ?>
</head>
<body>

<style>
.highlighttxt{
	background-color: #3993ba;border-radius: 4px;padding: 5px 4px;margin: 2px;color: #fff;
}
</style>
<div id="maincontainer" class="clearfix"> 
		<!-- header -->
		<header>
				<?php require_once('include/top-header.php');?>
		</header>
		
		<!-- main content -->
		<div id="contentwrapper">
				<div class="main_content">
						<nav>
								<div id="jCrumbs" class="breadCrumb module">
										<ul>
												<li> <a href="home.php"><i class="icon-home"></i></a> </li>
												<li> <a href="index.php">Dashboard</a> </li>
												<li> <a href="#">Job Applications</a> </li>
												<?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
												<li> <a href="jobapp.php"> Add new Job Application</a> </li>
												<?php }?>
												<?php include_once("include/curr_selection.php"); ?>
										</ul>
								</div>
						</nav>
						<div id="validation" ><span style="color:#00CC00;font-size:18px">
								<?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
								</span></div>
						<br/>
						<div class="row-fluid">
								<div class="span12">
										<table class="table table-striped table-bordered dataTable no-footer" id="job_apps" role="grid" aria-describedby="job_apps_info" style="width: 100%;" width="100%">
												<thead>
														<tr role="row">
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 71px;" aria-label="ID: activate to sort column ascending">ID</th>
																<th class="sorting_asc" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 230px;" aria-label="Name: activate to sort column ascending">Name</th>
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 287px;" aria-label="Email: activate to sort column ascending">Email</th>
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 146px;" aria-label="Telephone: activate to sort column ascending">Telephone</th>
																<th class="sorting_desc" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 193px;" aria-label="Last Modified: activate to sort column ascending" aria-sort="descending">Last Modified</th>
																<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 418px;" aria-label="Download CV">Download CV</th>
														</tr>
												</thead>
												<tbody>
														<tr role="row" class="odd">
																<td>132157</td>
																<td class="sorting_2">Tony Roberts</td>
																<td>jobs4tony.roberts@btinternet.com</td>
																<td>07872109017</td>
																<td class="sorting_1">19 Jul 2017,12:52 AM</td>
																<td>
			Lorem Ipsum is simply dummy<span CLASS="highlighttxt" > text of the printing</span> and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has <span CLASS="highlighttxt" >survived not only</span> five centuries
</td>
														</tr>
														<tr role="row" class="even">
																<td>132156</td>
																<td class="sorting_2">Andrew Booth</td>
																<td>andrew.booth@boothie.co.uk</td>
																<td>+447889510489</td>
																<td class="sorting_1">19 Jul 2017,12:48 AM</td>
																<td>
			Lorem Ipsum is simply dummy<span CLASS="highlighttxt" > text of the printing</span> and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has <span CLASS="highlighttxt" >survived not only</span> five centuries
</td></tr>
														<tr role="row" class="odd">
																<td>132155</td>
																<td class="sorting_2">Stephen Giles</td>
																<td>steve-giles1@hotmail.co.uk</td>
																<td>07843244008</td>
																<td class="sorting_1">19 Jul 2017,12:46 AM</td>
																<td>&nbsp;</td>
														<td>
			Lorem Ipsum is simply dummy<span CLASS="highlighttxt" > text of the printing</span> and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has <span CLASS="highlighttxt" >survived not only</span> five centuries
</td></tr>
												</tbody>
										</table>
								</div>
						</div>
				</div>
		</div>
		<aside>
				<?php require_once('include/sidebar.php');?>
		</aside>
		<?php require_once('include/footer.php');?>
		<!-- datatable --> 
		<script src="lib/datatables/jquery.dataTables.min.js"></script> 
		<script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script> 
		<!-- datatable functions --> 
		<script src="js/datatables.js"></script> 
</div>
</body>
</html>