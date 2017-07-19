<?php
	//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
	if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
	?>
	<div class="control-group">
		<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
		 <div class="ui-widget" style="margin-left: 160px;">
			<select onChange="" name="site_id" id="site_id_sel"  style="width:350px">
			<option value=""></option>
			<?php
			if($site=$db->get_row("select id, GUID, name,Code from sites where ID='$site_id'")){ ?>
					<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
				<?php 
			}else{
				$sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
				foreach($sites as $site){ ?>
				<option value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
				<?php }
			}				
			?>
			</select>
		</div>
		<span class="help-block">&nbsp;</span>
	</div>
	<?php }
	elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	$site_arr=explode("','",$_SESSION['site_id']);
	if(count($site_arr)>1) {
	?>
	<div class="control-group">
		<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
		<div class="ui-widget" style="margin-left: 160px;">
			<select onChange="" name="site_id" id="site_id_sel" >
			<option value=""></option>
				<?php
				if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
					foreach($sites as $site){ ?>
						<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
					<?php }
				}else {
					$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100 ");
					foreach($sites as $site)
					{
					?>
					<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
					<?php } 
				} ?>
			</select>
			<span class="help-block">&nbsp;</span>
		</div>
	</div>
	
	<?php
	} else {
?>
<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
<?php
} }
?>	