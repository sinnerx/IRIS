<script src="<?php echo url::asset("backend/tools/tinymce/js/tinymce/tinymce.min.js"); ?>"></script>
<script src="<?php echo url::asset("backend/js/pimgallery.js"); ?>"></script>
<script type="text/javascript">
	
var newsletter = new function()
{
	this.preview = function()
	{
		$("#edit-box").slideUp();

		var template = tinymce.activeEditor.getContent();

		$.ajax({url:pim.base_url+'ajax/newsletter/getPreviewContent', type: 'POST', data:{template: template}}).done(function(txt)
		{
			$("#preview-container-body").html(txt).parent().slideDown();
		});
	}

	this.closePreview = function()
	{
		$("#preview-container").slideUp();
		$("#edit-box").slideDown();
	}

	this.testsend = function()
	{
		var data = {template: tinymce.activeEditor.getContent()};

		pim.loader.start("#edit-box");
		$.ajax({url:pim.base_url+'ajax/newsletter/testSend', type: 'POST', data:data}).done(function(txt)
		{
			alert(txt);
		});
	}

	this.includeModule = function(type)
	{
		var types = {activity:'{site.activities}', article: '{site.articles}'};
		selection.setContent(types[type]);
	}
};

var selection = null;

tinymce.init({
	selector:'textarea.mce',
	menubar: false,
	toolbar: ["undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | mybutton | yourbutton ourbutton"],
	relative_urls: false,
	remove_script_host: false,
	setup : function(ed) {
		// Add a custom button
        ed.addButton('mybutton', {
		title : 'Insert/edit Photo',
		image : '<?php echo url::asset("backend/tools/tinymce/js/tinymce/24_upload.png"); ?>',
		onclick : function() {
			// Add you own code to execute something on click
			ed.focus();
			selection = ed.selection;
	        }
        });

        ed.addButton('yourbutton', {
		title : 'Add Activities',
		// image : '<?php echo url::asset("backend/tools/tinymce/js/tinymce/24_upload.png"); ?>',
		// icon : 'spellchecker',
		text: '+ Add Activity',
		onclick : function() {
			// Add you own code to execute something on click
			ed.focus();
			selection = ed.selection;
			newsletter.includeModule('activity');
	        }
        });

        ed.addButton('ourbutton', {
		title : 'Add Articles',
		// image : '<?php echo url::asset("backend/tools/tinymce/js/tinymce/24_upload.png"); ?>',
		// icon: 'browse',
		text: '+ Add Article',
		onclick : function() {
			// Add you own code to execute something on click
			ed.focus();
			selection = ed.selection;
			newsletter.includeModule('article');
	        }
        });
    }
});

function setUrl(res)
{
	selection.setContent("<p contenteditable='false' style='text-align:center;'><img style='max-width:100%;' src='"+res.photoUrl+"' /></p><br/>");
}

$(document).ready(function()
{
	setTimeout(function()
	{
		$("#mce_14").attr("data-toggle","ajaxModal").attr("onclick","pimgallery.start(this,setUrl);");
	},500);
});

</script>
<h3 class='m-b-xs text-black'>
Newsletter
</h3>
<div class='well well-sm'>
Configure the content of your newsletter. This newsletter can be sent using the 'Push' menu.</div>
<?php echo flash::data();?>
<form method='post'>
	<div class="row">
		<div class='col-sm-8'>
			<div class="panel panel-default" id='edit-box'>
				<div class="panel-body">
					<div class='form-group'>
						<?php echo form::text('siteNewsletterSubject','class="form-control" placeholder="Newsletter subject template"', $row['siteNewsletterSubject']);?>
					</div>
					<div class="form-group">
						<?php echo form::textarea("siteNewsletterTemplate","size='40' style='height: 300px;' class='mce'", $row['siteNewsletterTemplate']);?>
						<?php echo flash::data('siteNewsletterTemplate');?>
					</div>
				</div>
				<div>
					<input type='submit' class="btn btn-primary" value="Save" />
					<!-- <input type='button' class="btn btn-primary" value="Test Send" onclick='newsletter.testsend();' /> -->
					<input type='button' class='btn btn-default' value="Preview" onclick="newsletter.preview();" />
				</div>
			</div>
			<div class='panel panel-default' id='preview-container' style="display:none;">
				<div class='panel-body' id='preview-container-body'>

				</div>
				<div>
					<input type='button' class='btn btn-default' value="Close Preview" onclick="newsletter.closePreview();" />
				</div>
			</div>
		</div>
	</div>
</form>