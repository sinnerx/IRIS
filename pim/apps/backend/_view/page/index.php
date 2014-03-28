<script src="<?php echo url::asset("_scale/js/wysiwyg/jquery.hotkeys.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/bootstrap-wysiwyg.js");?>"></script>

<script src="<?php echo url::asset("_scale/js/wysiwyg/demo.js");?>"></script>
<script type="text/javascript">

var base_url	= "<?php echo url::base();?>/";
var page	= function()
{
	this.getParamURL	= function()
	{
		var url	= window.location.href;
		var param = url.split("#")[1];

		if(!param)
		{
			return false;
		}

		var paramR	= param.split("/");

		return paramR;
	}

	this.populatePage	= function(row)
	{
		var type	= row['pageType'];
		var name	= type == 1?row['pageDefaultName']:row['pageName'];
		var pageID	= row['pageID'];

		$("#pageEditor").hide();
		$("#pageTitle").html(row['pageTitle']);
		$("#pageLabel").html(row['pageLabel']);
		$("#pageContent").html(row['pageContent'] == ""?"Empty":row['pageContent']);

		$("#pageImageUrl").html(!row['pageImageUrl']?"No photo uploaded yet.":"<b>"+row['pageImageUrl']+"</b>");

		$("#button_edit").attr("href","#"+pageID+"/"+"edit");

		$("#pagelist"+pageID).addClass("activ");
	}

	this.getPage	= function(pageID,callback)
	{
		//reset active list.
		$(".pagelist.activ").removeClass("activ");

		if(pageID && !callback)
			window.location.href	= "#"+pageID;
		
		var pageID	= pageID?pageID:$($(".pagelist")[0]).attr("data-pageid");

		$.ajax({type:"POST",url:base_url+"ajax/page/pageDetail/"+pageID}).done(function(txt)
		{
			var row	= JSON.parse(txt);
			page.populatePage(JSON.parse(txt));

			if(callback)
			{
				callback(txt);
			}
		});
	}

	this.getEditPage	= function(pageID)
	{
		var pageID	= pageID?pageID:this.getParamURL()[0];

		//populate first.
		this.getPage(pageID,function(row)
		{
			//reparse again, and i don't know why.
			var row	= JSON.parse(row);
			var pageName	= row['pageTitle'];

			//insert <input/>
			$("#pageTitle").html("<input type'text' id='pageName' value='"+pageName+"' />");

			//show pageeditor
			$("#pageEditor").show();

			$("#pageContent").html("");
			$("#editor").show().html(row['pageContent']);

			//pageID
			$("#pageID").val(row['pageID']);
		});
	}

	// this function pending waiting for the incoming phase.
	this.getAddPage		= function()
	{

	}

	this.updatePage		= function()
	{
		var data	= {pageName:$("#pageName").val(),pageText:$("#editor").html()};
		var pageID	= $("#pageID").val();

		

		$.ajax({type:"POST",url:base_url+"ajax/page/pageUpdate/"+pageID,data:data}).done(function(txt)
		{
			console.log(txt);
			page.getPage(pageID);
			page.updateMenu(pageID,data['pageText']);

			//upload image
			page.pageUploadImage();


		});
	}

	this.pageUploadImage	= function()
	{
		var pageID	= $("#pageID").val();

		$("#upload-form")[0].action	+= pageID;
		$("#upload-form")[0].submit();

		//should be uploaded by now.
	}

	//update image link.
	this.updateImage	= function(imgUrl)
	{
		$("#pageImageUrl").html(imgUrl);
	}

	this.cancelUpdate	= function()
	{
		this.getPage($("#pageID").val());
	}

	this.updateMenu		= function(pageID,txt)
	{
		var txt	= txt.replace(/<[^>]*>/g,"");

		$("#pagelist"+pageID+" .menuPageText").html(txt);
	}

	//to initiate first page load.
	this.initiateDetail	= function()
	{
		var param	= this.getParamURL();

		if(!param)
		{
			this.getPage();
		}
		else
		{
			if(param.length == 1)
			{
				//got only one param, and it's an ID.
				this.getPage(param[0]);
			}
			else if(param[1] == "edit")
			{
				//got more than one param.
				this.getEditPage(param[0]);
			}
			return false;
		}
	}

}
var page = new page();

$(document).ready(function()
{
	//get first page based on list.
	page.initiateDetail();
});

function showFilter()
{
	$("#filterpage").slideDown();
	$("#pages").css("top","150px");
}

 </script>
 <style type="text/css">

#filterpage
{
	background:#f9f9f9;
	padding:5px;
	margin-right: 2px;
	display: none;
}

#filterpage div
{
	font-weight: bold;
	border-bottom: 2px solid #f2f4f8;
}

#filterpage label
{
	padding:5px 5px 0 5px;
}

#pages
{
	-webkit-transition:top 0.8s;
}

.pagelist.activ
{
	background: #e7f4fd;
	border-left:1px solid #009bff;
}

#pageTitle
{
	font-weight: bold;
}

#pageTitle input
{
	width:100%;
	border:1px solid #d0d8e6;
	font-size:0.8em;
	padding:5px;
	position: relative;
	top:-5px;
	left:-5px;
}


#pageEditor
{
}

#editor
{
	font-size:0.9em;
	min-height:200px;
}
	

.wrapper
{
	position: relative;
}

 </style>
 <section class='hbox stretch'>
 <aside class="aside-lg" id="email-list">
	<section class="vbox">
	<header class="dker header clearfix">
	  <div class="btn-group pull-right">
	    <button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-left"></i></button>
	    <button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-right"></i></button>
	  </div>
	  <div class="btn-toolbar">
	  	<button onclick='showFilter();' class='btn btn-sm btn-bg btn-default pull-left'>Filter</button>
	  	<button class='btn btn-sm btn-bg btn-default pull-right'>Add +</button>
	    <div class="btn-group select" style='display:none;'>
	      <button class="btn btn-default btn-sm btn-bg dropdown-toggle" data-toggle="dropdown">
	        <span class="dropdown-label" style="width: 65px;">Default</span>

	        <span class="caret"></span>
	      </button>
	      <ul class="dropdown-menu text-left text-sm">
	        <li><a href="#">Default</a></li>
	        <li><a href="#">Normal</a></li>
	      </ul>
	    </div>
	    <!-- Refresh button
	    <div class="btn-group">
	      <button class="btn btn-sm btn-bg btn-default" data-toggle="tooltip" data-placement="bottom" data-title="Refresh"><i class="fa fa-refresh"></i></button>
	    </div> -->
	    
	  </div>
	</header>
	<div id='filterpage'>
	    	<div>Filter by page type :</div>
	    	<label style='display:block;'>
	    		<input type='checkbox' checked /> Default
	    	</label>
	    	<label style='display:block;'>
	    		<input type='checkbox' checked /> Normal
	    	</label>
	    </div>
	<!-- Page list -->
	<section id='pages' class="scrollable hover w-f">
	  <ul class="list-group auto no-radius m-b-none m-t-n-xxs list-group-lg">
	  	<!-- 
	    <li class="list-group-item">
	      <a href="#" class="clear text-ellipsis">
	        <small class="pull-right">3 minuts ago</small>
	        <strong class="block">Drew Wllon</strong>
	        <small>Wellcome and play this web application template  </small>
	      </a>
	    </li> -->
	    <?php
		if($res_page)
		{
			## loop.
			foreach($res_page as $row):
				$type	= $row['pageType'];
				$pageName	= $type == 1?$pageDefault[$row['pageDefaultType']]['pageDefaultName']:$row['pageName'];
				$pageText	= $row['pageText']?$row['pageText']:"<span style='opacity:0.5;'>Empty</span>";
				$pageText	= preg_replace("/<[^>]*>/","",$pageText);	## remove any html markup.

				$createdDate	= strtotime($row['pageCreatedDate']);
				$pageID			= $row['pageID'];

				?>
		<li class='list-group-item pagelist' id='pagelist<?php echo $pageID;?>' onclick='page.getPage(<?php echo $pageID;?>);' data-pageid='<?php echo $pageID;?>'>
			<a href='javascript:void(0);' class='clear text-ellipsis'>
				<small class='pull-right'>Some minutes ago</small>
				<strong class='block'><?php echo $pageName;?></strong>
				<small class='menuPageText'><?php echo $pageText;?></small>
			</a>
		</li>
			<?php endforeach;
			## /loop.
		}

		?>
	  </ul>
	</section>
	<!-- /Page list -->
	<footer class="footer dk clearfix">
	  <form class="m-t-sm">
	    <div class="input-group">
	      <input type="text" class="input-sm form-control input-s-sm" placeholder="Search">
	      <div class="input-group-btn">
	        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
	      </div>
	    </div>
	  </form>
	</footer>
	</section>
	</aside>

    <aside id="email-content" class="bg-light lter">
      <section class="vbox">
        <section class="scrollable">
          <div style="background:#f2f4f8;" class="wrapper clearfix">
            <!-- <a href="#" data-toggle="class" class="pull-left m-r-sm"><i class="fa fa-star-o fa-lg text"></i><i class="fa fa-star text-warning fa-lg text-active"></i></a> -->
            <!-- Title -->
            <h4 class="m-n" style='display:block;height:20px;' id='pageTitle'>[ Page Title ]</h4>
          </div>
          <div>
          	<!-- page header -->
            <div class="block clearfix wrapper b-b">
              <a href='#' class='fa fa-trash-o pull-right' style='font-size:18px;'></a>
              <a href='#' id='button_edit' onclick='page.getEditPage();' class='fa fa-edit pull-right' style='font-size:18px;'></a>
              <label id='pageLabel'></label>
            </div>
            <!-- /page header -->
            <div class="wrapper">
<div class='imagebox'>
Photo : <span id='pageImageUrl'></span>
</div>
<div id='pageContent'>

</div>
            	<!-- main content -->
<div id='pageEditor'>
<div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#editor">
	<div class="btn-group">
	<ul class="dropdown-menu">
	</ul>
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
	<li><a data-edit="fontSize 2"><font size="2">Normal</font></a></li>
	<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
	</ul>
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
	<a class="btn btn-default btn-sm" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
	<a class="btn btn-default btn-sm" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
	<a class="btn btn-default btn-sm" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
	<a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
	<a class="btn btn-default btn-sm" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
	<a class="btn btn-default btn-sm" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
	<a class="btn btn-default btn-sm" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
	<a class="btn btn-default btn-sm" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
	<a class="btn btn-default btn-sm" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
	<div class="dropdown-menu">
	<div class="input-group m-l-xs m-r-xs">
	<input class="form-control input-sm" placeholder="URL" type="text" data-edit="createLink"/>
	<div class="input-group-btn">
	<button class="btn btn-default btn-sm" type="button">Add</button>
	</div>
	</div>
	</div>
	<a class="btn btn-default btn-sm" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
	</div>

	<div class="btn-group hide">
	<a class="btn btn-default btn-sm" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
	<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
	</div>
	<div class="btn-group">
	<a class="btn btn-default btn-sm" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
	<a class="btn btn-default btn-sm" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
	</div>
	<div class='btn-group'>
	<a class="btn btn-default btn-sm" title="Upload Photo" onclick='$(".upload-button").slideToggle();'><i class="fa fa-picture-o"></i></a>
	<div class='upload-button' style='position:relative;top:5px;left:5px;display:none;'>
		<form method='post' id='upload-form' target='upload-iframe' action='<?php echo url::base("page/uploadImage/");?>' enctype="multipart/form-data"><?php echo form::file("pageImage");?>
		<iframe style='display:none;' name='upload-iframe'></iframe></form>
	</div>
	</div>
</div>
<div id="editor" class="form-control" style="overflow:scroll;">
</div>
<input type='hidden' name='pageID' id='pageID' />
<div class'row' style='padding-top:5px;'>
	<input type='button' class='btn' value='Preview' />
	<input type='button' class='btn btn-primary pull-right' value='Save' onclick='page.updatePage();' />
	<input type='button' class='btn btn-default pull-right' value='Cancel' onclick='page.cancelUpdate();' />
</div>
</div>
            	<!-- /main content -->
            </div>
          </div>
        </section>
      </section>
    </aside>
</section>