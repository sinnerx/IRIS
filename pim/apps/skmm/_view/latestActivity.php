<div class="blueBox">
<span class="activity">Aktiviti Semasa</span>
    <div class="blueBoxContent1">
    <table width="100%">
    <?php
    if($res_activity):
    foreach($res_activity as $row):?>
    <tr>
        <td width="80" align="center"><?php echo date("d F Y",strtotime($row['activityStartDate']));?></td>
        <td><?php echo $row['activityName'];?></td>
      </tr>
    <?php
    endforeach;
    else:?>
    <div style="padding-left:10px;">
    Tiada aktiviti terkini
    </div>
    <?php
    endif;
    ?>
      <!-- <tr>
        <td width="80" align="center">15 Apr 2014</td>
        <td>Hari Terbuka PI1M</td>
      </tr>
      <tr>
        <td align="center">25 Apr  2014</td>
        <td>Bengkel Latihan Microsoft Office</td>
      </tr>
      <tr>
        <td align="center">16 Mei 2014</td>
        <td>Klik Dengan Bijak</td>
      </tr>
      <tr>
        <td align="center">02 Jun 2014</td>
        <td>Bengkel Latihan E-Dagang</td>
      </tr>
      <tr>
        <td align="center">27 Jul 2014</td>
        <td>Bengkel Pemasaran Facebook</td>
      </tr> -->
    </table>
    <center><a href="<?php echo url::base("{site-slug}/aktiviti");?>">lihat semua aktiviti</a></center>
    </div>
<div class="blueBoxRibbon"></div>
</div>