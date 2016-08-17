<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/tokenizer.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.css"); ?>" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
 <!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>
 // <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->

<script type="text/javascript">
	
	var base_url	= "<?php echo url::base();?>/";

	$(document).ready(function() {

	$("#selectDateStart").on("changeDate", function(ev)
	{
		var category	= $("#category").val() != ""?"&category="+$("#category").val():"";  		
		var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
		var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#category")[0]) {
        		var category = "<?php echo $category ?>";
	   		}

	   		window.location.href	= base_url+"site/article?"+category+selectDateStart+selectDateEnd;
		});

	$("#selectDateEnd").on("changeDate", function(ev)
		{

			var category	= $("#category").val() != ""?"&category="+$("#category").val():"";  		
			var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
			var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#category")[0]) {
        		var category = "<?php echo $category ?>";
	   		}

	   		window.location.href	= base_url+"site/article?"+category+selectDateStart+selectDateEnd;
		});

		function getParameterByName(name) {
    		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        	results = regex.exec(location.search);
    		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	});
	

	var articleCategory	= new function()
	{	
		this.select	= function()
		{					
			var category	= $("#category").val() != ""?"&category="+$("#category").val():"";  		
		var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
		var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#category")[0]) {
        		var category = "<?php echo $category ?>";
	   		}

	   		window.location.href	= base_url+"site/article?"+category+selectDateStart+selectDateEnd;
		
		}
	}
// $(document).ready(function()
// {
// 	var selected = '<?php echo $learningIsSelected; ?>';
// 	if( selected == 1){
// 		alert("Selected");
// 	}



// 	// if($("#learningselect").val() == 2){
// 	// 	;
// 	// }
// 	$("#startdate").change(function(){
// 		$( "#startdate").datepicker("option","dateFormat","yy-mm-dd");
// 	});

// 	$("#enddate").change(function(){
// 		$( "#enddate").datepicker("option","dateFormat","yy-mm-dd");
// 	});
// });

pim.uriHash.addCallback({"event":function(){activity.showTypeDetail(1)},"training":function(){activity.showTypeDetail(2)},"others":function(){activity.showTypeDetail(99)}});

</script>

<style type="text/css">

#table-slider td
{
	position: relative;
}

#table-slider img
{
	border:1px solid #d6ddeb;
}

.articleName
{
	font-weight: bold;
}

.general-label
{
	position:absolute;
	right:0px;
	opacity: 0.5;
}

.tags-article{
	width:200px !important;
	
}

</style>

<script type="text/javascript">

function buttonCheck()
{
	
		if(!confirm("Are you sure want to post this article on facebook page."))
		{
			return false;
		}
	

	return true;
}

var article = new function($)
{
	this.delete = function(id)
	{
		if(!confirm('Delete this article?'))
			return false;

		window.location.href = '<?php echo url::base("site/deleteArticle/");?>'+id;
	}

	this.undelete = function(id)
	{
		if(!confirm('Cancel deletion of this article?'))
			return false;

		window.location.href = '<?php echo url::base("site/undeleteArticle/");?>'+id;
	}
}(jQuery);

</script>


<h3 class="m-b-xs text-black">
<a href='info'>My Blog Posts</a>
</h3>
<div class='well well-sm'>
List of all your approved and pending blog articles.
</div>
	<?php echo flash::data();?>

<section class="panel panel-default">
	<div class='row'>
	<div class='col-sm-10'>
	<form method='post' action='article' class="form-inline bs-example">
                 
			
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("category",$catList,"class='input-sm form-control input-s-sm inline v-middle' onchange='articleCategory.select();'",request::get("category"),"[SELECT CATEGORY]");?>

			</div>
			
			<div  class="form-group" style="margin-left:10px">
			From <?php echo form::text("selectDateStart","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateStart)));?>
					
			</div>
			<div  class="form-group" style="margin-left:10px">
			To  <?php echo form::text("selectDateEnd","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateEnd)));?>

	
			</div>
		
            </form> 
            <br/>
            </div>
</div>           

<div class="table-responsive">
	<table id='table-slider' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th class="th-sortable" data-toggle="class">Title
			</th>
			<th>Tag(s)</th>
			<th>Date to be Published</th>			
			<th colspan="3"></th>
		</tr>
	</thead>
	<tbody>
		<?php
	 if($article):
		$requestdata = model::load('site/request')->replaceWithRequestData('article.update', array_keys($article));
		/*if($data){
			array_merge($article,$data);
		}echo '<pre>';print_r($data);die;*/
		$no	= pagination::recordNo();
		//var_dump($requestdata);
		foreach($article as $row):
		
		if(isset($requestdata[$row['articleID']])){
			$row = array_merge($row,$requestdata[$row['articleID']]);
		}

		$active		= $row['articleStatus'] == 1?"active":"";
		$opacity	= $row['articleStatus'] == 0 || isset($requestdata[$row['articleID']])?"style='opacity:0.5;'":"";
		$href		= ($row['articleStatus'] == 1?"deactivate":"activate")."?".$row['articleID'];
		$row['articleStatus'] = $row['articleStatus'] == 1 && isset($requestdata[$row['articleID']])?4:$row['articleStatus'];
		$href		= "?toggle=".$row['articleID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no++;?>.</td>
			<td width='40%'>
			<div class='articleName'><?php echo $row['articleName'];?></div>
			</td>
			<td>
				<div class="tokenizer tags-article" style="border: 0px;background-color: transparent;">
					<div>
						<ul class="token">
							<?php
								if($articleTags[$row['articleID']]):
								foreach($articleTags[$row['articleID']] as $tag):
							?>
							<li><span class="label"><?php echo $tag['articleTagName']; ?></span></li>
							<?php
								endforeach;
								endif;
							?>
							<li><span class="input" contenteditable="false"></span></li>
						</ul>
					</div>
				</div>
			</td>
			<td><?php echo date("d-m-Y",strtotime($row['articlePublishedDate']));?></td>
			<!--<td>
			</td>
			<td>
			</td>-->
			<td colspan = "3">
				<a><?php echo model::load('template/icon')->status($row['articleStatus']); ?></a>
				<?php  if (authData('site.siteInfoFacebookPageId') != ""  ) { ?>
					<a class="fa fa-facebook-square" style="color:#44609d;" onclick ='return buttonCheck();' href="<?php echo url::base('facebook/getArticleInfo');?>?articleID=<?php echo $row['articleID']; ?>"></a>
				<?php } ?>			
				<?php if($row['articleStatus'] != 2):?>
					<a href='<?php echo url::base("site/editArticle/".$row['articleID']);?>' class='fa fa-edit'></a>
					<?php if($row['articleStatus'] == 5):?>
						<a href='javascript:article.undelete(<?php echo $row['articleID'];?>);' class='i i-cross2'></a>
					<?php else:?>
						<a href='javascript:article.delete(<?php echo $row['articleID'];?>);' class='i i-cross2'></a>
					<?php endif;?>				
				<?php endif; ?>
			</td>
		</tr>
		<?php 
		endforeach;
		?>
		<?php
		else:?>
			<tr>
				<td align="center" colspan='7'>No blog was posted yet.</td>
			</tr>
		<?php endif;?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
	<div class="row">
		<div class="col-sm-12">
			<?php echo pagination::link();?>
		</div>
	</div>
</footer>
</section>