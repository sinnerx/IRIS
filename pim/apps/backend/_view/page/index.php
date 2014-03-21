 <script type="text/javascript">

var page	= function()
{
	this.getPage	= function(pageSlug)
	{
		var paramR	= !pageSlug?this.getParam():Array();


	}

	this.getParam	= function()
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

}
var page = new page();

$(document).ready(function()
{
	
});

 </script>
 <section class='hbox stretch'>
 <aside class="aside-lg" id="email-list">
	<section class="vbox">
	<header class="dker header clearfix">
	  <div class="btn-group pull-right">
	    <button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-left"></i></button>
	    <button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-right"></i></button>
	  </div>
	  <div class="btn-toolbar">
	  	<button class='btn btn-sm btn-bg btn-default pull-left'>Filter</button>
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
	<!-- Page list -->
	<section class="scrollable hover w-f">
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
				$createdDate	= strtotime($row['pageCreatedDate']);
				?>
		<li class='list-group-item'>
			<a href='#' class='clear text-ellipsis'>
				<small class='pull-right'>Some minutes ago</small>
				<strong class='block'><?php echo $pageName;?></strong>
				<small><?php echo $pageText;?></small>
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
          <div class="wrapper dk  clearfix">
            <a href="#" data-toggle="class" class="pull-left m-r-sm"><i class="fa fa-star-o fa-lg text"></i><i class="fa fa-star text-warning fa-lg text-active"></i></a>
            <!-- Title -->
            <h4 class="m-n">[ Page Title ]</h4>
          </div>
          <div>
          	<!-- page header -->
            <div class="block clearfix wrapper b-b">
              <a href='#' class='fa fa-trash-o pull-right' style='font-size:18px;'></a>
              <a href='#' class='fa fa-edit pull-right' style='font-size:18px;'></a>
            </div>
            <!-- /page header -->
            <div class="wrapper">
            	<!-- main content -->

            	<!-- /main content -->
            </div>
          </div>
        </section>
      </section>
    </aside>
</section>