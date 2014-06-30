<link rel="stylesheet" href="<?php echo url::asset("frontend/css/blog.css"); ?>" type="text/css" />
<div class="body-container clearfix">

	<div class="lft-container">

		<a href="<?php echo url::base(request::named("site-slug")."/blog/"); ?>"><h3 class="block-heading">BLOG</h3></a>
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
									<img src="<?php echo $img;?>" width="1600" height="1205"  alt=""/> 
								</div>
								<div class="right-blog">
									<div class="top-heading">
										<h3><a href="<?php echo url::base(request::named("site-slug")."/blog/".date('Y',strtotime($row['articlePublishedDate']))."/".date('m',strtotime($row['articlePublishedDate']))."/".$row['articleSlug']);?>"><?php echo $row['articleName']; ?></a></h3>
										<div class="story-info">
											<span class="story-author">Ditulis Oleh <a href="#"><?php echo $row['articleCreatedUser']; ?> </a></span>
											<span class="story-date">Pada <a href="#"><?php echo date("jS F Y",strtotime($row['articleCreatedDate'])); ?></a></span>
											
											<?php
												$count = 0;
												$item	= "";
						                		foreach($row['category'] as $cat):
						                			$count++;
						                			if($cat['checked']){
						                				$item	.= $cat['categoryName'].'&nbsp;&nbsp;';
						                			}
						                			if($cat['child']):
						                				foreach($cat['child'] as $c):
							                				$count++;
							                				if($c['checked']){
							                					$item .= $c['categoryName'].'&nbsp;&nbsp;';	
							                				}
								                			if($count == 4){
								                				break;
								                			}
						                				endforeach;
						                			endif;

						                			if($count == 4){
						                				break;
						                			}
						                		endforeach;
						                	?>
						                	<?php if($item != ""):?>
						                	<span class="story-category">Dalam <a href="#">
						                		<?php echo $item;?>
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