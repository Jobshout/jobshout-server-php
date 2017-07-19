<?php require_once("connect.php"); 
$RelatedSitesGUID='';
if($SiteGUID= $db->get_row("SELECT GUID FROM sites where ID ='".$_POST['site_id']."' ")){
											
											if($RelatedSitesGUID = $db->get_results("SELECT uuid_related_site FROM wi_related_sites where  uuid_master_site = '$SiteGUID->GUID'")){ ?>
																			<?php 
																			foreach($RelatedSitesGUID as $relatedsite){
																				$relatedSiteguid=$relatedsite->uuid_related_site;
																				$site = $db->get_row("SELECT Name, GUID FROM sites where GUID = '$relatedSiteguid'");
																				$relatedSiteName=$site->Name;
																				$relatedSiteGUID=$site->GUID;
																				
																				?>
																																					
																					<input type="checkbox" name="document_share_uuid[]" id="document_share_uuid" value="<?php echo $relatedSiteGUID; ?>"  />
																				<?php echo $relatedSiteName;?>
																				<br/>
																			<?php } ?>
										<?php 	} 
										}
										
			if($RelatedSitesGUID=='') {
				 echo "No Site available for sharing";
			}

?>