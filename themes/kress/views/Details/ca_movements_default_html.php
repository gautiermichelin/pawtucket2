<?php
	# --- acquisitions
	
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");
	$vn_pdf_enabled = 		$this->getVar("pdfEnabled");	
	AssetLoadManager::register('mirador');

	$o_icons_conf = caGetIconsConfig();
	$va_type_specific_icons = $o_icons_conf->getAssoc("placeholders");
	if(!($vs_default_placeholder = $o_icons_conf->get("placeholder_media_icon"))){
		$vs_default_placeholder = "<i class='fa fa-picture-o fa-2x' aria-label='placeholder image'></i>";
	}
	$t_list_item = new ca_list_items();
	$t_list_item->load($t_item->get("type_id"));
	$vs_typecode = $t_list_item->get("idno");
	$vs_placeholder = caGetPlaceholder($vs_typecode, "placeholder_media_icon");
	if(!$vs_placeholder){
		$vs_placeholder = $vs_default_placeholder_tag;
	}

	$vb_ajax			= (bool)$this->request->isAjax();

	if($vb_ajax){
?>
		<div class="detail detailPreviewContainer">		
			<div class="detailPreview">
				<div class="detailPreviewClose pointer" onclick="caMediaPanel.hidePanel(); return false;"><span class="glyphicon glyphicon-remove-circle"></span></div>
				<div class="row detailPreviewContent">
					<div class="col-sm-10 col-sm-offset-1 height100">			
<?php
						# --- preview panel linked to from image search/browse results
						if(is_array($media_value_ids = $t_item->get("ca_movements.media.media_media.value_id", ["returnAsArray" => true])) && sizeof($media_value_ids)) {
							foreach($media_value_ids as $value_id) {
								print caGetMediaViewerHTML($this->request, "attribute:".$value_id, $t_item, array("inline" => true, "display" => "detail", "context" => "acquisitions"));
							}
						}else{
						#if($vs_image = $t_item->get("ca_movements.media.media_media.large")){
						#	print caDetailLink($this->request, $vs_image, "", "ca_movements", $t_item->get("ca_movements.movement_id"));
						#}else{
?>
							<?php print caDetailLink($this->request, "<div class='detailPreviewImgPlaceholder'>".$vs_placeholder."</div>", "", "ca_movements", $t_item->get("ca_movements.movement_id")); ?>
<?php
						}
?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
<?php
						if($vs_previous_url = $this->getVar("previousURL")){
							print "<a href='#' onclick='caMediaPanel.showPanel(\"".$vs_previous_url."\"); return false;'><div class='detailPreviewPreviousLink'><i class='fa fa-angle-left'></i><div class='small'>Prev</div></div></a>";
						}
?>
					</div>
					<div class="col-xs-8">
						<div class="unit">
							<label>{{{Distribution<ifdef code="ca_movements.idno">: ^ca_movements.idno</ifdef>}}}</label>
							{{{^ca_movements.preferred_labels.name}}}
						</div>
							<p><?php print caDetailLink($this->request, "View Record", "btn btn-default", "ca_movements", $t_item->get("ca_movements.movement_id")); ?></p>							
					</div>
					<div class="col-xs-2">
<?php
						if($vs_next_url = $this->getVar("nextURL")){
							print "<a href='#' onclick='caMediaPanel.showPanel(\"".$vs_next_url."\"); return false;'><div class='detailPreviewNextLink'><i class='fa fa-angle-right'></i><div class='small'>Next</div></div></a>";
						}
?>
					</div>
				</div>
			</div><!-- end detailPreview -->				
		</div>		
<?php
	}else{
?>
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
		<div class="container">
			<div class="row">
				<div class='col-sm-12 col-md-5'>
<?php
					// 
					// Uses new ".value_id" suffix to get value_ids for all repeats of ca_movements.media.media_media.value_id
					//
					if(is_array($media_value_ids = $t_item->get("ca_movements.media.media_media.value_id", ["returnAsArray" => true])) && sizeof($media_value_ids)) {
						foreach($media_value_ids as $value_id) {
							print caGetMediaViewerHTML($this->request, "attribute:".$value_id, $t_item, array("inline" => true, "display" => "detail", "context" => "acquisitions"));
						}
					}else{
?>
						<div class="detailImgPlaceholder"><?php print $vs_placeholder; ?></div>
<?php						
					}
					
					//
					// This is updated to use the new "includeValueIDs" option and works now as well
					//
					// $va_media = $t_item->get("ca_movements.media.media_media", ["returnWithStructure" => true, 'includeValueIDs' => true]);
// 					if(is_array($va_media) && sizeof($va_media)){
// 						$va_media = array_pop($va_media);
// 						foreach($va_media as $vn_attribute_id => $va_media){
// 							print caGetMediaViewerHTML($this->request, "attribute:".$va_media['media_media_value_id'], $t_item, array("inline" => true, "display" => "detail", "context" => "acquisitions"));
// 						}
// 					}
?>
				</div><!-- end col -->
				<div class='col-sm-12 col-md-5'>
					<H2>{{{^ca_movements.type_id<ifdef code="ca_movements.idno">: ^ca_movements.idno</ifdef>}}}</H2>
					<H1>{{{^ca_movements.preferred_labels.name}}}</H1>
					<div class="grayBg">
						<div class="row">
							{{{<ifcount code="ca_entities" restrictToRelationshipTypes="seller" min="1"><div class="col-sm-6"><div class="unit"><label data-toggle="popover" title="Seller" data-content="Seller">Seller</label><unit relativeTo="ca_entities" restrictToRelationshipTypes="seller" delimiter="<br/>"><l>^ca_entities.preferred_labels.displayname</l></div></unit></div></ifcount>}}}
							{{{<ifdef code="ca_movements.Acquisition_Date"><div class="col-sm-6"><div class="unit"><label data-toggle="popover" title="Date" data-content="Date">Date</label>^ca_movements.Acquisition_Date</div></div></ifdef>}}}							
						</div>
						<div class="row">
							{{{<ifdef code="ca_movements.Acquisition_ObjectCount"><div class="col-sm-6"><div class="unit"><label data-toggle="Number of Objects" title="Number of Objects" data-content="Distributions">Number of Objects</label>^ca_movements.Acquisition_ObjectCount</div></div></ifdef>}}}
							{{{<ifdef code="ca_movements.Acquisition_PriceUSD"><div class="col-sm-6"><div class="unit"><label data-toggle="popover" title="Group Purchase Price" data-content="Group Purchase Price">Group Purchase Price</label>^ca_movements.Acquisition_PriceUSD</div></div></ifdef>}}}
						</div>
						<div class="row">
							{{{<ifdef code="ca_movements.Acquisition_FinalPayDate"><div class="col-sm-6"><div class="unit"><label data-toggle="popover" title="Final Pay Date" data-content="FInal Pay Date">Final Pay Date</label>^ca_movements.Acquisition_FinalPayDate</div></div></ifdef>}}}
							{{{<ifdef code="ca_movements.Acquisition_Location"><div class="col-sm-6"><div class="unit"><label data-toggle="popover" title="Location" data-content="Location">Location</label>^ca_movements.Acquisition_Location</div></div></ifdef>}}}
						</div>
						{{{<ifdef code="ca_movements.Acquisition_Source"><div class="unit"><label data-toggle="popover" title="Citation" data-content="Citation">Citation</label>^ca_movements.Acquisition_Source</div></ifdef>}}}
					</div>
					{{{<ifdef code="ca_movements.Acquisition_Note">
						<div class='unit'><label data-toggle="popover" title="Note" data-content="Note">Note</label>
							<span class="trimText">^ca_movements.Acquisition_Note</span>
						</div>
					</ifdef>}}}				
				</div><!-- end col -->
				<div class='col-sm-12 col-md-2'>
					<div id="detailTools">
	<?php
						if($vs_download_link = $t_item->get("ca_movements.media.media_media.original.url")){
							print "<div class='detailTool'><span class='glyphicon glyphicon-download' aria-label='"._t("Download Media")."'></span><a href='".$vs_download_link."'>Download Media</a></div>";
						}
						if ($vn_pdf_enabled) {
							print "<div class='detailTool'><span class='glyphicon glyphicon-file' aria-label='"._t("Summary")."'></span>".caDetailLink($this->request, "PDF Summary", "", "ca_movements",  $t_item->get("ca_movements.movement_id"), array('view' => 'pdf', 'export_format' => '_pdf_summary'))."</div>";
						}
						print "<div class='detailTool'><span class='glyphicon glyphicon-link' aria-label='"._t("Permalink")."'></span> <a href='#' onClick='$(\"#permalink\").toggle(); return false;'>Permalink</a><br/><textarea name='permalink' id='permalink' class='form-control input-sm' style='display:none;'>".$this->request->config->get("site_host").caDetailUrl($this->request, 'ca_movements', $t_item->get("ca_movements.movement_id"))."</textarea></div>";					

	?>
					</div>
				</div>
			</div><!-- end row -->
{{{<ifcount code="ca_objects" min="1">
			<div class="row">
				<div class="col-sm-12"><HR/>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div id="browseResultsDetailContainer" class="results">
						<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>
					</div><!-- end browseResultsContainer -->
				</div>
			</div><!-- end row -->
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery("#browseResultsDetailContainer").load("<?php print caNavUrl($this->request, '', 'Browse', 'objects', array('facet' => 'movement_facet', 'id' => '^ca_movements.movement_id', 'showFilterPanel' => 1), array('dontURLEncodeParameters' => true)); ?>", function() {
						//jQuery('#browseResultsContainer').jscroll({
						//	autoTrigger: true,
						//	loadingHtml: '<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>',
						//	padding: 20,
						//	nextSelector: 'a.jscroll-next'
						//});
					});
					
					
				});
			</script>
</ifcount>}}}		</div><!-- end container -->
	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->
<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 120
		});
		var options = {
			placement: function () {
				if ($(window).width() > 992) {
					return "left";
				}else{
					return "auto top";
				}

			},
			trigger: "hover",
			html: "true"
		};

		$('[data-toggle="popover"]').each(function() {
			if($(this).attr('data-content')){
				$(this).popover(options).click(function(e) {
					$(this).popover('toggle');
				});
			}
		});
	});
</script>
<?php
}
?>