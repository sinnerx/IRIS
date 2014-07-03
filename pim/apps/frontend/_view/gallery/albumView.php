<!-- FlexSlider -->
<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<script defer src="<?php echo url::asset("_templates/js/jquery.flexslider.js");?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/album.css");?>">
<script type="text/javascript">

  jQuery(function(){
    //SyntaxHighlighter.all();
  });
  jQuery(window).load(function(){
    jQuery('#carousel').flexslider({
      animation: "slide",
      controlNav: false,
      animationLoop: false,
      slideshow: false,
      itemWidth: 100,
      itemMargin: 5,
      asNavFor: '#slider-album'
    });

    jQuery('#slider-album').flexslider({
      animation: "slide",
      controlNav: false,
      animationLoop: false,
      slideshow: false,
      sync: "#carousel",
      start: function(slider){
        jQuery('body').removeClass('loading');
      }
    });
  });
</script>
<style type="text/css">
.flexslider
{
  border:0px;
}

.flexslider span
{
  background: white;
  display: block;
  background: black;
  height:400px;
}
.flexslider .flexslider-main img
{
  display:inline;
}
.thumbnail-album .slides > li
{
  border:0px;
}
.flexslider-thumb-list
{
  opacity: 0.3;
  cursor: pointer;
}
.flex-active-slide
{
  opacity: 1;
}


</style>
<h3 class="block-heading">
<a href='<?php echo url::base("{site-slug}");?>'>Home</a><span class="subheading"> >
<a href='<?php echo url::base("{site-slug}/gallery");?>'> Galeri Foto</a>
 > Album > Foto</span>
 </h3>
<div class="block-content clearfix">
<div class="page-content">
<div class="page-description"> 
<h4 class="album-heading"><?php echo $row_album['albumName'];?></h4>
</div>
<div class="page-sub-wrapper album-page clearfix">
<?php if($res_photo):?>
<section class="slider">
  <div class="flexslider" id="slider-album">
  <div class="flex-viewport" style="overflow: hidden; position: relative;">
    <ul class="slides" style="width: 2400%; transition-duration: 0s; transform: translate3d(-4632px, 0px, 0px);">
      <?php
      foreach($res_photo as $row)
      {
        $url  = model::load("image/services")->getPhotoUrl($row['photoName']);
        ?>
        <li style="width: 772px; text-align:center; float: left; display: block;" class="flexslider-main">
          <span><img height='400px' style="width:auto;" src="<?php echo $url;?>" draggable="false"></span>
        </li>

        <?php
      }
      ?>
    </ul>
    </div>
    <ul class="flex-direction-nav">
      <li><a href="#"><i class="fa fa-angle-left"></i></a></li>
      <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
      </ul>
  </div>
  <div class="flexslider thumbnail-album" id="carousel">
  <div class="flex-viewport" style="overflow: hidden; position: relative;">
    <ul class="slides" style="width: 2400%; transition-duration: 0s; transform: translate3d(-1290px, 0px, 0px);">
      <?php
      if($res_photo):
      foreach($res_photo as $row)
      {
        $url  = model::load("image/services")->getPhotoUrl($row['photoName']);
        ?>
        <li style="width: 100px; height:62px; float: left; display: block;" class="flexslider-thumb-list">
          <img src="<?php echo $url;?>" draggable="false">
        </li>
        <?php
      }
      endif;
      ?>
    </ul>
    </div>
      <ul class="flex-direction-nav"><li><a href="#"><i class="fa fa-angle-left"></i></a></li>
      <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
      </ul>
    </div>
  </section>
<?php endif;?>
  <div class="album-info">
    <?php echo nl2br($row_album['albumDescription']);?>
  </div>
</div>
<div class="clr"></div>
  <?php /*

  ### ALBUM COMMENT HOLD ON.
  <div class="forum-post-comment">
    <div class="forum-post-comment-count">KOMEN <span>(3)</span></div>
      <div class="forum-post-comment-content">
      <ul><li class="clearfix">
      <div class="forum-post-comment-avatar"> <img src="members_photo/1538817_10202447454481584_450404680_n.jpg" alt=""/> </div>
      <div class="forum-post-comment-message">
      <div class="forum-post-comment-info">Mohd Hafiz
        <div class="comment-post-date"><i class="fa fa-clock-o"></i>  2 Jam Lalu</div></div>
      Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
      </div>
      </li>
      <li class="clearfix">
      <div class="forum-post-comment-avatar"> <img src="members_photo/1901223_587997637960391_2111595029_n.jpg" alt=""/> </div>
      <div class="forum-post-comment-message">
      <div class="forum-post-comment-info">Nurul Syuhadah Mansoor <div class="comment-post-date"><i class="fa fa-clock-o"></i>  3 Jam Lalu</div></div>
      Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
      </div>
      </li>
      <li class="clearfix">
      <div class="forum-post-comment-avatar"> <img src="members_photo/1450051_630273793680835_492412226_n.jpg" alt=""/> </div>
      <div class="forum-post-comment-message">
      <div class="forum-post-comment-info">Razali Hussein
        <div class="comment-post-date"><i class="fa fa-clock-o"></i>  4 Jam Lalu</div></div>
      Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
      </div>
      </li>
      </ul>
      <div class="forum-comment-form">
      <div class="comment-user-avatar"></div>
      <div class="comment-post-input">
      <h3>Nama Komentator (Logged-in)</h3>
      <div class="comment-text-input">
      <div class="comment-text-input-arrow"></div>
      <textarea>Taipkan komen anda di sini...</textarea>
      <input type="submit" value="Hantar" class="bttn-submit">
      </div>
      </div>
      </div>
    </div>
  </div>*/?>
</div>
</div>