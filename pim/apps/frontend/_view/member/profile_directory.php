<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<!--Just add new JS For Calendar-->

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="<?php echo url::asset("frontend/js/jquery.easydropdown.js"); ?>"></script>
<!-- JS for slider -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo url::asset("frontend/js/member_scroller.js"); ?>"></script>
<style type="text/css">
	article {
	    margin-bottom: 3rem;
	    position: relative;
	    *zoom: 1;
	}

	article:before, article:after {
	    content: "";
	    display: table;
	}

	article:after { clear: both }

	input[type=checkbox] {
	    border: 0;
	    clip: rect(0 0 0 0);
	    height: 1px;
	    width: 1px;
	    margin: -1px;
	    overflow: hidden;
	    padding: 0;
	    position: absolute;
	}

	[for="read_more"] {
	    position: absolute;
	    bottom: -3rem;
	    left: 0;
	    width: 100%;
	    text-align: center;
	    padding: .65rem;
	    box-shadow: inset 1px 1px rgba(0, 0, 0, 0.1), inset -1px -1px rgba(0, 0, 0, 0.1);
	}

	[for="read_more"]:hover {
	    background: rgba(0,0,0,.5);
	    color: rgb(255,255,255);
	}
</style>

<script language="javascript" type="text/javascript">
var $h = jQuery.noConflict();
  
$h(function() {
    $h("#member_scroller1").member_scroller({
        scroller_title_show: 'disable',
        scroller_time_interval: '4000',
                  
        scroller_window_padding: '10',
            
        scroller_images_width: '92',
        scroller_images_height: '110',
        scroller_title_size: '12',
        scroller_title_color: 'black',
        scroller_show_count: '6',
        directory: 'images'
    });			
});

var userList = new function()
{
	this.usertype = 'alphabetical';

	this.userload = function(type)
	{
		this.usertype = type;
		jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/profile/getUserList/');?>"+type}).done(function(desc)
		{
			if(desc)
			{
				jQuery('.member-all-gallery ul').html('');
				jQuery('.member-all-gallery ul').html(desc);
				jQuery('.member-all-gallery ul').animate({"opacity":"0.3"},"slow");
				jQuery('.member-filter').find('.filter-active').attr('href','javascript:userList.userload(\''+jQuery('.member-filter').find('.filter-active').attr('id')+'\');');
				jQuery('.member-filter').find('.filter-active').attr('class','');
				jQuery('#'+type).attr('class','filter-active');
				jQuery('#'+type).attr('href','#');
				jQuery('.member-all-gallery ul').animate({"opacity":"1"},"slow");
			}
		});
	}
	this.getMore = function(page)
	{
		jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/profile/getUserList/"+this.usertype+"?page="+page+"');?>"}).done(function(desc)
		{
			if(desc)
			{
				jQuery('.member-all-gallery ul article').remove();
				jQuery('.member-all-gallery ul').append(desc);
				jQuery('.member-all-gallery ul').animate({"opacity":"0.3"},"slow");
				jQuery('.member-all-gallery ul').animate({"opacity":"1"},"slow");
			}
		});
	}
}

jQuery(document).ready(function(){
	jQuery("#search").keyup(function(){

		var char = jQuery('#search').val();
		var data = {find:char};

		jQuery.ajax({type:"POST",data:data,url:"<?php echo url::base('{site-slug}/profile/getUserList/search');?>"}).done(function(desc)
		{
			if(desc)
			{
				jQuery('.member-all-gallery ul').html('');
				jQuery('.member-all-gallery ul').html(desc);
				jQuery('.member-filter').find('.filter-active').attr('href','javascript:userList.userload(\''+jQuery('.member-filter').find('.filter-active').attr('id')+'\');');
				jQuery('.member-filter').find('.filter-active').attr('class','');
				jQuery('#'+type).attr('class','filter-active');
				jQuery('#'+type).attr('href','#');
			}
		});
	});
});
</script>       

<div class="body-container clearfix">
	<div class="lft-container">
		<h3 class="block-heading">Profil Direktori <span class="subheading"> > Direktori Ahli</span></h3>
		<div class="block-content clearfix">
			<div class="page-content">
				<!-- <div class="page-description"> 
Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.
				</div> -->
				<div class="page-sub-wrapper calendar-page clearfix">
					<?php if(count($new_users) > 0): ?>
					<div class="activity-type-desc">
						<div class="members-page-heading">Ahli terbaru</div>
					</div>
					<div class="clr"></div>
					<div class="member-showcase" style="height:232px;">
						<div id="member_scroller1" class="member_scroller">
							<div class="member_scroller_mask" style="height:232px !important;">
								<ul>
									<?php foreach ($new_users as $user): ?>
									<li>
										<a href="#" title="<?php echo $user['userProfileFullName']; ?>">
											<?php if($user['userPremiumStatus'] == 1){ ?><div class="member-status">Pemegang Kad</div><?php } ?>
											<img src="<?php echo model::load("api/image")->buildAvatarUrl($user['userProfileAvatarPhoto']); ?>" width="60" height="60" alt="title"/>
										</a>
										<div class="member-info">
											<div class="member-name"><?php echo $user['userProfileFullName']; ?></div>
											<?php if($user['userProfileOccupation']){ ?><div class="member-level"><?php echo $user['userProfileOccupation']; ?></div><?php } ?>
										</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<ul class="member_scroller_nav">
								<li></li>
								<li></li>
							</ul>
							<div style="clear: both"></div>
						</div>
					</div>
					<?php endif; ?>
					<div class="members-page-heading">Senarai Ahli 
						<div class="member-search">
							<form>
								<input id="search" type="search" value="Carian..." onblur="if(this.value == '') { this.value='Carian...'}" onfocus="if (this.value == 'Carian...') {this.value=''}">
							</form>
						</div>
					</div>
					<div class="member-showcase-list">
						<div class="member-filter">
							<a href="#" id="alphabetical" class="filter-active">Mengikut Abjad</a>
							<a href="javascript:userList.userload('date');" id="date">Mengikut Tarikh</a>
							<a href="javascript:userList.userload('lastLogin');" id="lastLogin">Mengikut Keaktifan</a>
						</div>
						<div class="member-all-gallery">
							<ul>
								<?php controller::load("member","getUserList/alphabetical"); ?>
							</ul>
						</div>
					</div>
						<div class="clr"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
	var $s = jQuery.noConflict();
    $s(window).load(function() {
    $s('#slider').nivoSlider();
		
    });
    </script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">

			function DropDown(el) {
    this.dd = el;
    this.initEvents();
}
DropDown.prototype = {
    initEvents : function() {
        var obj = this;
 
        obj.dd.on('click', function(event){
            $(this).toggleClass('active');
            event.stopPropagation();
        }); 
    }
}
		</script>