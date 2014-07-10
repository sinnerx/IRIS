<link rel="stylesheet" href="<?php echo url::asset("frontend/css/blog.css"); ?>" type="text/css" />
<div class="lft-container">
	<div class="top-heading">
	
	</div>
	<div class="block-content clearfix">
		<div class="page-content">
			<div class="page-sub-wrapper blog-page">
  				<div class="blog-container">
  					<div class="story-info">
						<span class="story-author">
                        <h3 class="block-heading">
                            <?php
                            echo model::load("template/frontend")
                            ->buildBreadCrumbs(Array(
                            			Array("Blog",url::base(request::named("site-slug").'/blog')),
                            			Array("Baca")
                            						));

                            ?>
                            </h3>
						</span>
					</div>
					<!-- <div class="single-featured-image">
						<?php 
							/*if(strpos($article['articleText'],'<img') !== false)
							{
								$length = (strpos($article['articleText'],'alt')-4)-(strpos($article['articleText'],'src')+2);
								$start = (strpos($article['articleText'],'src=')+5);
							}*/
						?>
						<img src="<?php //if(strpos($article['articleText'],'<img') !== false){ echo substr($article['articleText'],$start,$length); } ?>" width="1600" height="1205"  alt=""/> 
					</div> -->
						<div class="single-blog-wrap">
							<div class="top-heading">
								<h3><a><?php echo $article['articleName']; ?></a></h3>
								<div class="story-info">
									<span class="story-author">Ditulis Oleh <a href="#"><?php echo $article['articleEditedUser']; ?> </a></span>
									<span class="story-date">Pada <a href="#"><?php echo date('jS F Y', strtotime($article['articlePublishedDate'])); ?></a></span>
									<?php
									$flag	= false;
									foreach($category as $cat)
									{
										if($cat['checked'])
											$flag	= true;
									}

								 	if($flag):?>
									<span class="story-category">Dalam 
										<a href="#">
										<?php
											$count = 0;
					                		foreach($category as $cat):
					                			$count++;
					                			if($cat['checked']){
					                				echo $cat['categoryName'].'&nbsp;&nbsp;';
					                			}
					                			if($cat['child']):
					                				foreach($cat['child'] as $c):
						                				$count++;
						                				if($c['checked']){
						                					echo $c['categoryName'].'&nbsp;&nbsp;';
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
					                	</a>
									</span>
									<?php endif;?>
								</div>
							</div>
							<div class="short-story" style="clear: both;">
								<?php 
									if(strpos($article['articleText'],'<img') !== false)
									{
										$start = strpos($article['articleText'],'<img');
										$length = strpos(substr($article['articleText'],$start),'/>')+2;
									}
								?>
								<?php echo $article['articleText']; ?>
							</div>
							<div class="blog-additional-info">
								<?php if($activity[0]['activityID']): ?>
								<div class="events">
									<a href="<?php echo model::load('helper')->buildDateBasedUrl($activity[0]['data']['activitySlug'],$activity[0]['data']['activityStartDate'],url::base(request::named("site-slug").'/activity')); ?>">
										<i class="fa fa-calendar"></i> <span class="event-heading"><?php echo $activity[0]['data']['activityName']; ?>:</span> <?php if($activity[0]['data']['activityAddress']): ?><span> <strong>Tempat Lokasi :</strong> <?php echo $activity[0]['data']['activityAddress']; ?></span><?php endif; ?> <span><strong>Tarikh:</strong> <?php echo date('jS F Y', strtotime($activity[0]['data']['activityStartDate'])); ?></span> <span> <strong>Masa:</strong> 10.00 Pagi</span>
									</a>
								</div>
							<?php endif; ?>
								<div class="tags">
									<span>Tags:</span>
									<?php
										foreach($articleTags as $tag){
									?>
									<a href="<?php echo url::base("{site-slug}/blog/tag/$tag[articleTagName]");?>"><?php echo $tag['articleTagName']; ?></a> 
									<?php
										}
									?>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>