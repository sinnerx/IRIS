<div class="columnL">
        <h1>Jadual Latihan ICT</h1> 
        <table width="100%" border="1">
        <tr>
          <th scope="col" width="100px">Tarikh</th>
          <th scope="col">Aktiviti</th>
        </tr>
        <?php if($res_training):?>
        <?php foreach($res_training as $row_training):?>
        <tr>
          <td><?php echo date("d F Y",strtotime($row_training['activityStartDate']));?></td>
          <td><?php echo $row_training['activityName'];?></td>
        </tr>
        <?php endforeach;?>
        <?php else:?>
          <tr>
            <td colspan="2">Tiada aktiviti terkini</td>
          </tr>
        <?php endif;?>
         <!--  <tr>
            <th scope="col" width="100px">Tarikh</th>
            <th scope="col">Aktiviti</th>
          </tr>
          <tr>
            <td>15 Apr 2014</td>
            <td>Hari Terbuka PI1M</td>
          </tr>
          <tr>
            <td>25 Apr  2014</td>
            <td>Bengkel Latihan Microsoft Office</td>
          </tr>
          <tr>
            <td>16 Mei 2014</td>
            <td>Klik Dengan Bijak</td>
          </tr>
          <tr>
            <td>02 Jun 2014</td>
            <td>Bengkel Latihan E-Dagang</td>
          </tr>
          <tr>
            <td>27 Jul 2014</td>
            <td>Bengkel Pemasaran Facebook (Facebook Marketing)</td>
          </tr>
          <tr>
            <td>15 Apr 2014</td>
            <td>Hari Terbuka PI1M</td>
          </tr>
          <tr>
            <td>25 Apr  2014</td>
            <td>Bengkel Latihan Microsoft Office</td>
          </tr>
          <tr>
            <td>16 Mei 2014</td>
            <td>Klik Dengan Bijak</td>
          </tr>
          <tr>
            <td>02 Jun 2014</td>
            <td>Bengkel Latihan E-Dagang</td>
          </tr>
          <tr>
            <td>27 Jul 2014</td>
            <td>Bengkel Pemasaran Facebook (Facebook Marketing)</td>
          </tr> -->
        </table>
        </div>
        
        <div class="columnR">
        <h1>Jadual Aktiviti</h1>
        <table width="100%" border="1">
          <tr>
            <th scope="col" width="100px">Tarikh</th>
            <th scope="col">Aktiviti</th>
          </tr>
          <?php if($res_event):?>
          <?php foreach($res_event as $row_event):?>
          <tr>
            <td><?php echo date("d F Y",strtotime($row_event['activityStartDate']));?></td>
            <td><?php echo $row_event['activityName'];?></td>
          </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr>
            <td colspan="2">Tiada aktiviti terkini</td>
          </tr>
          <?php endif;?>

          <!-- <tr>
            <td>15 Apr 2014</td>
            <td>Hari Terbuka PI1M</td>
          </tr>
          <tr>
            <td>25 Apr  2014</td>
            <td>Bengkel Latihan Microsoft Office</td>
          </tr>
          <tr>
            <td>16 Mei 2014</td>
            <td>Klik Dengan Bijak</td>
          </tr>
          <tr>
            <td>02 Jun 2014</td>
            <td>Bengkel Latihan E-Dagang</td>
          </tr>
          <tr>
            <td>27 Jul 2014</td>
            <td>Bengkel Pemasaran Facebook (Facebook Marketing)</td>
          </tr>
          <tr>
            <td>15 Apr 2014</td>
            <td>Hari Terbuka PI1M</td>
          </tr>
          <tr>
            <td>25 Apr  2014</td>
            <td>Bengkel Latihan Microsoft Office</td>
          </tr>
          <tr>
            <td>16 Mei 2014</td>
            <td>Klik Dengan Bijak</td>
          </tr>
          <tr>
            <td>02 Jun 2014</td>
            <td>Bengkel Latihan E-Dagang</td>
          </tr>
          <tr>
            <td>27 Jul 2014</td>
            <td>Bengkel Pemasaran Facebook (Facebook Marketing)</td>
          </tr> -->
        </table>
		</div>