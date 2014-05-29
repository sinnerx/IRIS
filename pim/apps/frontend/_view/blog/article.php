<style type="text/css">
.blog-container{
	
}

.blog-container ul{
	padding:0px;
	margin:0px;
	list-style:none;
	
}



.blog-container ul li{
	border-bottom:1px solid #E8E8E8;
	padding-bottom:20px;
	margin-bottom:20px;
	
}


.blog-container ul li:last-child{
	border-bottom:0px;
	padding-bottom:0px;
	
}



.featured-image{
	width:150px;
	height:150px;
	float:left;
	
	
}

.featured-image img{
	width:100%;
	height:auto;
	border-bottom:4px solid #E8E8E8;
	
	
}

.right-blog{
	float:right;
	width:500px;
	
}

.top-heading h3{
	
	margin-top:0px;
	color: #009BFF;
    font-family: 'Lato',sans-serif;
    font-size: 25px;
    font-weight:bold;
	margin-bottom:0px;
}
	
	.top-heading h3 a{
		color: #009BFF;
		text-decoration:none;
		 -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
		
	}
	
	.top-heading h3 a:hover{
	color:#0062A1;	
	}
	
.story-info{
	font-size:12px;
	color:#7d7d7d;
	margin-bottom:10px;
	
}

.story-info a{
	color:#009BFF;
	font-weight:bold;
	
}


.story-info span{
	margin-right:10px;
	display:inline-block;
	
}


.short-story{
	color: #666666;
    font-family: Arial;
    font-size: 12px;
	
}

.story-read-more{
	margin-top:20px;
	margin-bottom:20px;
	
}

.story-read-more a{
	background:#009BFF;
	padding:5px 20px;
	color:#FFF;
	font-size:12px;
	text-transform:uppercase;
	 -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
	
}

.story-read-more a:hover{
	background:#0062A1;
	
}

.single-featured-image{
	width:680px;
	height:300px;
	overflow:hidden;
	
}


.single-featured-image img{
	width:100%;
	height:auto;
	
}


.single-blog-wrap{
	margin-top:25px;
	
}

.blog-additional-info{

border-bottom:1px solid #E8E8E8;
border-top:1px solid #E8E8E8;
font-size:12px;
padding-top:15px;
padding-bottom:15px;
margin-top:30px;
	
}


.tags{

	
}



.tags a{
	color:#b1b1b1;
	border-right:1px solid #E8E8E8;
		display:inline-table;
		margin-right:15px;
		padding-right:15px;
		line-height:12px;
		 -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
	
}


.tags span{
	font-weight:bold;
	color:#333;
	text-transform:uppercase;
	display:inline-table;
	margin-right:20px;
	
}

.tags a:hover{
	color:#009BFF;
	
	
	
}

.events{
	padding-bottom:15px;
	margin-bottom:15px;
border-bottom:1px solid #EAEAEA;

	
}


.events i{
	margin-right:5px;
	color:#009BFF;
	
}

span.event-heading{
	font-weight:bold;
	font-size:15px;
	color:#009BFF;
	display:inline;
	
	
}

.events a span.event-heading{
	color:#009BFF;
	
}
	
.events span{
	margin-right:10px;
	font-size:14px;
	
}

.events a span{
	color:#000;
	
}
.events span strong{
	margin-right:5px;
	
	
}
</style>
<div class="body-container clearfix">

	<div class="lft-container">

		<h3 class="block-heading">BLOG</h3>
		<div class="block-content clearfix">

			<div class="page-content">

				<div class="page-sub-wrapper blog-page">

  					<div class="blog-container">
	    				<?php 
	    					if($article):
	    				?>
	    				<ul>
	    				<?php
							foreach($article as $row):
	    				?>
							<li class="clearfix">
								<div class="featured-image">
									<img src="images/blog_story.JPG" width="1600" height="1205"  alt=""/> 
								</div>
								<div class="right-blog">
									<div class="top-heading">
										<h3><a href="#"><?php echo $row['articleName']; ?></a></h3>
										<div class="story-info">
											<span class="stpry-author">Ditulis Oleh <a href="#"><?php echo $row['articleCreatedUser']; ?> </a></span>
											<span class="story-date">Pada <a href="#"><?php echo date("d-m-Y",strtotime($row['articleCreatedDate'])); ?></a></span>
											<span class="story-category">Dalam <a href="#">
											<?php
												foreach($row['articleTags'] as $tag):
													echo $tag['articleTagName'].'&nbsp;&nbsp;';
												endforeach;
											?>
											</a> </span>
										</div>
									</div>
									<div class="short-story">
										<?php echo $row['articleText']; ?>
									</div>
									<div class="story-read-more"><a href="#">Baca Lagi</a></div>
								</div>
							</li>
						<?php
							endforeach;
						?>
						</ul>
						<?php
							else:
						?>
	    				<ul>
	    					<h3>No blog was added yet.</h3>
						</ul>
						<?php
							endif;
						?>
					</div>

				</div>

			</div>
		
		</div>
	
	</div>

</div>