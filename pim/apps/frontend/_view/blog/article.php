<link rel="stylesheet" href="<?php echo url::asset("frontend/css/blog.css"); ?>" type="text/css" />
<style type="text/css">

.pagination-wrapper > div
{
	text-align: center;
}
.pagination-wrapper > div a:nth-child(2)
{
	border-left:1px solid #c6def0;
}
.pagination-wrapper > div a
{
	padding:5px;
}

</style>
<div class="body-container clearfix">
	<div class="lft-container">
		<h3 class="block-heading"><a href='<?php echo url::base("{site-slug}");?>'>HOME</a> > <a href="<?php echo url::base(request::named("site-slug")."/blog/"); ?>">BLOG</a>
		<?php
		## bread build simple breadcrump. later need new one.
		if($typeSortBy):
		echo " > ";
		echo strtoupper($typeSortBy);
		echo " > ".$typeSortByValue;
		elseif($dateSortBy):
		echo " > ";
		echo "<a href='".url::base("{site-slug}/blog/$year")."'>$year</a>";
			if($month):
				echo " > ";
				echo $month;
			endif;
		elseif($userSortBy):
		echo " > BY > $userProfileFullName";
		endif;
		?>
		</h3>
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
									<?php 
										if(strpos($row['articleText'],'<img') !== false)
										{
											$length = (strpos($row['articleText'],'alt')-4)-(strpos($row['articleText'],'src')+2);
											$start = (strpos($row['articleText'],'src=')+5);
											$img	= substr($row['articleText'],$start,$length);
										}
										else
										{
											$img 	= model::load("image/services")->getPhotoUrl(null);
										}
									?>
									<img src="<?php echo $img;?>" style='height:100%;' alt=""/> 
								</div>
								<div class="right-blog">
									<div class="top-heading">
										<h3><a href="<?php echo url::base(request::named("site-slug")."/blog/".date('Y',strtotime($row['articlePublishedDate']))."/".date('m',strtotime($row['articlePublishedDate']))."/".$row['articleSlug']);?>"><?php echo $row['articleName']; ?></a></h3>
										<div class="story-info">
											<span class="story-author">
											<?php
											$userUrl	= url::base("{site-slug}/blog/user/".$row['articleCreatedUser']);
											?>
											Ditulis Oleh <a href="<?php echo $userUrl;?>"><?php echo $row['userProfileFullName']; ?> </a></span>
											<span class="story-date">Pada <?php
											$dateR	= explode(" ",date("jS F Y",strtotime($row['articlePublishedDate'])));

											list($articleMonth,$articleYear) = explode(" ",date("m Y",strtotime($row['articlePublishedDate'])));

											$monthUrl	= url::base("{site-slug}/blog/$articleYear/$articleMonth");
											$yearUrl	= url::base("{site-slug}/blog/$articleYear");

											echo $dateR[0]." "."<a href='$monthUrl'>$dateR[1]</a> <a href='$yearUrl'>$dateR[2]</a>"; ?></span>
											
											<?php
												$item	= Array();

												if(isset($categoryR[$row['articleID']]))
												{
													foreach($categoryR[$row['articleID']] as $row_cat)
													{
														$url	= url::base("{site-slug}/blog/category/".$row_cat['categoryID']);
														$item[] = "<a href='$url'>".$row_cat['categoryName']."</a>";
													}
												}
						                	?>
						                	<?php if($item):?>
						                	<span class="story-category">Dalam <a href="#">
						                		<?php echo implode(", &nbsp;&nbsp;",$item);?>
											</a> </span>
						                	<?php endif;?>
										</div>
									</div>
									<div class="short-story">
										<?php 
											if(strpos($row['articleText'],'<img') !== false)
											{
												$start = strpos($row['articleText'],'<img');
												$length = strpos(substr($row['articleText'],$start),'/>')+2;
											}
										?>
										<?php echo model::load('helper')->purifyHTML($row['articleText'],150); ?>
									</div>
									<div class="story-read-more"><a href="<?php echo url::base(request::named("site-slug")."/blog/".date('Y',strtotime($row['articlePublishedDate']))."/".date('m',strtotime($row['articlePublishedDate']))."/".$row['articleSlug']);?>">Baca Lagi</a></div>
								</div>
							</li>
						<?php
							endforeach;
						?>
						</ul>
						<!-- pagination -->
						<div class='pagination-wrapper'>
						<?php echo pagination::link();?>
						</div>
						<!-- /pagination -->
						<?php
							else:
						?>
	    				<ul>
	    					<h3>Tiada blog yang diterbitkan.</h3>
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