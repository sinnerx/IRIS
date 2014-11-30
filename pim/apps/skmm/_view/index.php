<h1>Berita & Aktiviti Terkini</h1>
<?php if($res_article):?>
<?php foreach($res_article as $row):?>
<div class="newsContent">
<h1><a href="#"><?php echo $row['articleName'];?></a></h1>
<span><?php echo date("d F Y",strtotime($row['articlePublishedDate']));?> | Berita Semasa</span>
<p><?php echo model::load("helper")->purifyHTML($row['articleText']);?> <a href="#">read more</a></p> 
</div>
<?php endforeach;?>
<a href="#">lagi berita & aktiviti</a>
<?php else:?>
Tiada berita terkini
<?php endif;?>
<!-- <div class="newsContent">
<h1><a href="#">Majlis Perasmian PI1M oleh Menteri Komunikasi dan Multimedia Malaysia</a></h1>
<span>22 Januari 2014 | Berita Semasa</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elit lacus, dapibus vulputate tellus eget, tincidunt 
commodo libero. Cras sit amet libero et nibh porta aliquam. Nulla facilisi. Pellentesque ornare mollis est. Aenean 
sed varius purus. Sed eleifend gravida ligula, sit amet hendrerit erat convallis non... <a href="#">read more</a></p> 
</div>

<div class="newsContent">
<h1><a href="#">Hari Terbuka PI1M 2014</a></h1>
<span>22 Januari 2014 | Aktiviti Terkini</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elit lacus, dapibus vulputate tellus eget, tincidunt 
commodo libero. Cras sit amet libero et nibh porta aliquam. Nulla facilisi. Pellentesque ornare mollis est. Aenean 
sed varius purus. Sed eleifend gravida ligula, sit amet hendrerit erat convallis non... <a href="#">read more</a></p> 
</div>

<div class="newsContent">
<h1><a href="#">Bengkel e-Dagang Sesi Pertama 2014</a></h1>
<span>22 Januari 2014 | Aktiviti Terkini</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elit lacus, dapibus vulputate tellus eget, tincidunt 
commodo libero. Cras sit amet libero et nibh porta aliquam. Nulla facilisi. Pellentesque ornare mollis est. Aenean 
sed varius purus. Sed eleifend gravida ligula, sit amet hendrerit erat convallis non... <a href="#">read more</a></p> 
</div>

<div class="newsContent">
<h1><a href="#">Lawatan Deligasi dari Kenya ke PI1M Kg. Sg. Masin</a></h1>
<span>22 Januari 2014 | Berita Semasa</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elit lacus, dapibus vulputate tellus eget, tincidunt 
commodo libero. Cras sit amet libero et nibh porta aliquam. Nulla facilisi. Pellentesque ornare mollis est. Aenean 
sed varius purus. Sed eleifend gravida ligula, sit amet hendrerit erat convallis non... <a href="#">read more</a></p> 
</div>

<div class="newsContent">
<h1><a href="#">Jadual Kelas ICT Sepanjang Tahun 2014</a></h1>
<span>22 Januari 2014 | Aktiviti Terkini</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elit lacus, dapibus vulputate tellus eget, tincidunt 
commodo libero. Cras sit amet libero et nibh porta aliquam. Nulla facilisi. Pellentesque ornare mollis est. Aenean 
sed varius purus. Sed eleifend gravida ligula, sit amet hendrerit erat convallis non... <a href="#">read more</a></p> 
</div> -->