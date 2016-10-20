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


.no-result{
	color:#999;
	font-style:italic;
	
}

.month-select
{
	letter-spacing: 1px;
	padding:5px;
	border:0px;
	cursor: pointer;
	font-size: 12px;
}

.dob-months
{
	float: right;
	font-size: 13px;
}
</style>
<script type="text/javascript">
	
function changeCategory()
{
	var catID	= jQuery("#category").val();
	if(catID == "")
	{
		return false;
	}
	var url		= "<?php echo url::base('{site-slug}/blog/kategori/');?>"+catID;
	window.location.href	= url;
}

</script>
<!-- <a href='<?php echo url::base("{site-slug}");?>'>HOME</a><span class="subbread"> > <a href="<?php echo url::base(request::named("site-slug")."/blog/"); ?>">BLOG</a> -->
<?php
$breadcrumb = Array();
$breadcrumb[] = Array("Blog",url::base(request::named("site-slug")."/blog/"));
if($typeSortBy): ## sort by tag or category
	$breadcrumb[] = Array(strtoupper($typeSortBy));
	$breadcrumb[] = Array($typeSortByValue);
elseif($dateSortBy): ## sort by date. (year or month)
	$breadcrumb[] = Array($year,url::base("{site-slug}/blog/$year"));
		if($month):
			$breadcrumb[] = Array($month);
		endif;
elseif($userSortBy): ## sort by user
	$breadcrumb[] = Array("By");
	$breadcrumb[] = Array($userProfileFullName);
endif;
?>
<h3 class="block-heading">
<?php
## build breadcrumbo
echo model::load("template/frontend")
->buildBreadCrumbs($breadcrumb);
?>
<div class="dobs clearfix">
	<div class='dob-months'>
		Kategori : <?php echo form::select("category",$categoryListR,"class='month-select' onchange='changeCategory();'",$currCatID,"[Pilihan]");?>
	</div>
</div>
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
								/*if(strpos($row['articleText'],'<img') !== false)
								{
									$offset	= strpos($row['articleText'],'<img');
									$length = (strpos($row['articleText'],'alt',$offset)-4)-(strpos($row['articleText'],'src')+2);
									$start = (strpos($row['articleText'],'src=')+5);
									$img	= substr($row['articleText'],$start,$length);
								}
								else
								{
									$img 	= model::load("image/services")->getPhotoUrl(null);
								}*/
								//$img	= model::load("helper")->getImgFromText($row['articleText']);
								library::_require("simple_html_dom");
								$imghtml = str_get_html($row['articleText']);

								$imgs = $imghtml->find('img');

								$img = $imgs[0]->src;

							?>
							<img src="<?php if(!$img){
								echo url::asset("frontend/images/noimage.png");
								}
								else{
								echo $img;

								};;?>" style='height:100%;' alt=""/> 
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
												$url	= url::base("{site-slug}/blog/kategori/".$row_cat['categoryID']);
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
					<div class="no-result">Tiada blog pernah diterbitkan.</div>
				</ul>
				<?php
					endif;
				?>
			</div>
		</div>
	</div>
</div>