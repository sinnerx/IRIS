<h1>Berita Terkini</h1>
<?php if($res_article):?>
<?php foreach($res_article as $row):
$articleID	= $row['articleID'];
?>
<div class="newsContent">
<h1><a href="<?php echo url::base("{site-slug}/blog/$articleID");?>"><?php echo $row['articleName'];?></a></h1>
<span><?php echo date("d F Y",strtotime($row['articlePublishedDate']));?> | <?php echo $res_category[$row['articleID']]['categoryName'];?></span>
<p><?php echo model::load("helper")->purifyHTML($row['articleText']);?> <a href="<?php echo url::base("{site-slug}/blog/$articleID");?>">read more</a></p> 
</div>
<?php endforeach;?>
<?php else:?>
Tiada berita terkini
<?php endif;?>