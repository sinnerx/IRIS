<div class="body-container clearfix">

<div class="lft-container">

<?php
    $breadcrumb = Array();
    $breadcrumb[] = Array("Galeri Video",url::base(request::named("site-slug")."/video/"));
?>

<h3 class="block-heading"><?php echo model::load("template/frontend")->buildBreadCrumbs($breadcrumb); ?> </h3>

<div class="block-content clearfix">

<div class="page-content">


<div class="clr"></div>

<style type="text/css">
    .folder
    {
        float:left;
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .panel
    {
      background-color: transparent;
      border: 1px solid transparent;
      padding: 25px;
    }
    .panel-heading
    {
      position: relative;
      text-align: center;
      height: 10px;
      padding: 5px;
      font-weight: bold;
    }
    .panel-pic
    {
      cursor: pointer;
    }
    .panel-pic img
    {
      width: 230px;
      height: 165px;
    }
    .panel-pic img:hover
    {
      width: 224px;
      height: 159px;
      border-style: solid;
      border-width: medium;
      border-color: red;
    }
</style>

<?php 
    foreach($albums as $album){
        $first_vid = model::load("video/album")->getVideoAlbumCover($album['videoAlbumID'],1);
?>
    <div class="folder">
      <section class="panel">
          <div class="panel-pic">
              <a href="<?php echo url::base(request::named("site-slug")."/video/".$album['videoAlbumSlug']); ?>"><img src="<?php if($album['videoAlbumThumbnail']){echo $album['videoAlbumThumbnail'];}else{echo model::load("video/album")->buildVideoUrl($first_vid['videoType'],$first_vid['videoRefID']);} ?>" /></a>
          </div>
          <div class="panel-heading">
              <?php echo $album['videoAlbumName']; ?>
          </div>
      </section>
    </div>
<?php
    }
?>





</div>




</div>


</div>



</div>