<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>contact insert</title>
</head>

<body>
<div class="span6">
							<h3 class="heading">Add Category</h3>
							<form class="form_validation_reg" method="post" action="">
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Category Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php $Guid = NewGuid(); echo $Guid; ?>" name="GUID" id="GUID" >
											<input type="text" name="name" class="span12" id="CategoryName" onKeyUp="generate_code()" onBlur="generate_code()" value=""/>
											
																					</div>
										<div class="span4">
											<label>Code <span class="f_req">*</span></label>
											<input type="text" class="span12" name="Code" id="Code" readonly="readonly" value="" />
											<span class="help-block">URL (SEO friendly)</span>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span4">
											<label>Category Group</label>
										    <select onChange="" name="category_gropuID">
                                              <option value="0">-- Not specified --</option>
                                              <?php
												$op = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Active='1' AND SiteID='".SITE_ID."' ORDER BY ID");
												foreach($op as $opt){
													$id=$opt->ID;
													$name=$opt->Name;
													?>
                                              <option  value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                              <?php
												}
											?>
                                            </select>
									  </div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" />
												Inactive
											</label>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID">
												<option value="0">-- Select User --</option>
											<?php
												$catgoriziedUserID = $UserID;
												$op = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' AND SiteID='".SITE_ID."' ORDER BY ID");
												foreach($op as $opt){
													$id=$opt->ID;
													$name=$opt->Name;
													?>
													<option value="<?php echo $id; ?>"><?php echo $name; ?></option>	
													<?php
												}
											?>
											</select>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Type <span class="f_req">*</span></label>
											<select onChange="" name="reg_type">
												<option value="">-- Select --</option>
												<option  value="page">page</option>
												<option value="product">product</option>
												<option  value="contact-option">contact-option</option>
											</select>
										</div>
										
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Date <span class="f_req">*</span></label>
											<div class="input-append date" id="dp2" data-date-format="mm/dd/yyyy">
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Sync_Modified"  value="" /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<button class="btn btn-inverse" type="submit" name="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							</form>
</body>
</html>
