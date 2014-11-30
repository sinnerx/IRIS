<script src="<?php echo url::asset("skmm/js/jquery.colorbox.js");?>"></script>
<script>
    $(document).ready(function(){
        $('a.gallery').colorbox({rel:'gal'});
    });
</script>

    <h1>Galeri</h1>
    <?php if($albums):?>   
    <?php foreach($albums as $row_album):?>
    <div class="galleryThumb"> 
    <?php if(isset($photos[$row_album['siteAlbumID']])):?>
    <div class="galleryTitle"><?php echo $row_album['albumName'];?> | <?php echo date("d F Y",strtotime($row_album['albumCreatedDate']));?></div>
        <?php foreach($photos[$row_album['siteAlbumID']] as $row_photo):
        $href   = model::load("api/image")->buildPhotoUrl($row_photo['photoName'],"small");;
        $title  = "";
        ?>
        <!-- <a class="gallery" title="PI1M Kg Sg Masin" rel="gal"> --><img src="<?php echo $href;?>" style='height:100px;width:100px;'><!-- </a> -->

        <?php endforeach;?>
    <?php endif;?>
    </div>
    <?php endforeach;?>
    <?php else:?>
    Tiada galeri terkini lagi.
    <?php endif;?>
    <!-- <div class="galleryTitle">PI1M Kg Sg Masin | 5 May 2014</div>

    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>  
    </div>

    <div class="galleryThumb">   
    <div class="galleryTitle">Hari Terbuka PI1M Kg Sg Masin | 30 April 2014</div>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>  
    </div>

    <div class="galleryThumb">   
    <div class="galleryTitle">Lawatan Delegasi Kenya | 15 Mac 2014</div>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>
    <a class="gallery" href="images/sgmasin/03.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/03.jpg"></a>
    <a class="gallery" href="images/sgmasin/01.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/01.jpg"></a>
    <a class="gallery" href="images/sgmasin/02.jpg" title="PI1M Kg Sg Masin" rel="gal"><img src="images/sgmasin/thumb/02.jpg"></a>  
    </div> -->