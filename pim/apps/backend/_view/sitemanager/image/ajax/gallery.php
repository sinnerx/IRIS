<style type="text/css">
.mb-header > .mb-tab, 
{
	font-weight: normal;
}
.ajxgal-new, .ajxgal-photos, .ajxgal-albums
{
	display: none;
}
.mb-content
{
	padding-top:10px;
}

.mb-tab.active
{
	font-weight: bold;
}
.mb-content .active
{
	display:block;
}

@media (min-width: 768px) {
	.modal-dialog
	{
		width:700px;
	}
}
.modal-footer
{
}

#newPhoto
{
	width:100%;
}
.photo-list > div
{
	margin-bottom:10px;
	padding:5px;
	box-shadow: 0px 0px 10px #787878;
}
.photo-list.active > div
{
	background: #f04d4d;
}

/*pagination*/
.ajxgal-pagination a
{

}

</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Image Gallery
			<span style='font-size:11px;'> You may select from existing photo/album, or through adding new photo.</span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class='mb-header'>
			<a href='javascript:pimgallery.showTab(1);' class='mb-tab active'>Add New</a> | 
			<a href='javascript:pimgallery.showTab(2);' class='mb-tab'>Existing Photos</a> | 
			<a href='javascript:pimgallery.showTab(3);' class='mb-tab'>Albums</a>
			</div>
			<div class='mb-content'>
				<div class='ajxgal-new active'> <!-- through adding new photo -->
					<div class='row'>
						<div class='col-sm-6' style="margin:auto;">
							<img id='newPhoto' style="display:none;" /> <!-- new photo container -->
							<form id='formUpload' method='post' enctype="multipart/form-data" target='uploadIframe' action='<?php echo url::base("ajax/gallery/photoUpload");?>'>
								<div class='form-group'>
									<div>1. Photo</div>
									<?php echo form::file("photoName");?>
									<div>2. Description (optional)</div>
									<?php echo form::textarea("photoDescription",'style="width:100%;height:100px;"');?>
									<div><input type='submit' value='Upload' class='btn btn-success' /></div>
								</div>
								
								<iframe src="" style="display:none;" name='uploadIframe'></iframe>
							</form>
						</div>
					</div>
				</div>
				<div class='ajxgal-photos'> <!-- no album photo -->
				</div>
				<div class='ajxgal-albums'> <!-- By albums -->
				</div>
			</div>
		</div>
		<div class="modal-footer">
		<input type='button' class='btn btn-primary' data-dismiss='modal' value='Select' onclick='return pimgallery.select(window.event);' />
		<!-- 
			<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
			<a href="#" class="btn btn-primary">Save</a> -->
		</div>
	</div><!-- /.modal-content -->
</div>