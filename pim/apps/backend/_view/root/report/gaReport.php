<link rel="stylesheet" href="<?php echo url::asset("_scale/css/datepicker/datepicker.css");?>" type="text/css" />

<h3 class='m-b-xs text-black'>
Overview
</h3>
<div class='well well-sm'>
Dashboard overview
</div>


<body>


<script type="text/javascript">
 
var options =  <?php echo json_encode($data['data2'], JSON_NUMERIC_CHECK); ?>;

$(function() {


$('#container').highcharts(options)

});
</script>


<form method='post'>
<div class='row'>
  <div class='col-sm-6'>



<div id="container" style="min-width: 150px; height: 400px; margin: 0 auto"></div>
  <div class='table-responsive'>
    <table class='table'>
      <tr>
        <th width='15px'>No.</th>
        <th width="300px">Page</th>
        <th>Page Views</th>
       
      </tr>
     
      <?php 

      $no = 1;
      foreach($data['data3'] as $row)
      {   


       ?>
      <tr <?php echo $opacity;?>>
        <td><?php echo $no++;?></td>
        <td><?php 

 if ($row['page'] == "") { $page = "(Laman Utama)"; } else { $page = $row['page'];}

        echo $page; ?></td>
        <td><?php echo $row['views'];?></td>
       
        <td>
          <center>
       
          <a href="<?php echo url::base('ajax/googleanalytics/detail'); ?>?siteID=<?php echo $siteID; ?>&page=<?php echo $row['page']; ?>&start=<?php echo $data['data1']['startDate']; ?>&end=<?php echo $data['data1']['endDate']; ?>" data-toggle="ajaxModal">
            <i class="fa fa-external-link"></i>
          </a>
          </center>
        </td>
      </tr>
      <?php
      }
      ?>
    
    </table>
    <br>
  </div>
  </div>


  <div class='col-sm-5'>
    <div class='table-responsive'>
      <table class='table'>
        <tr>


          <td width="200px">Google Analytics Manager <?php echo model::load("api/ga")->email; ?>
          </td>
          <td>
          <div class="doc-buttons">
             <a href="https://www.google.com/analytics/web/#report/visitors-overview/" target="_blank" class="btn btn-s-md btn-default">Go</a>                             </div>    
          </div>
        </td>

      </tr>
        <tr>
          <td>Site
          <?php echo flash::data("siteID");?>      
          </td>
          <td>
            <?php echo form::select("siteID",$sites,null, $siteID);?>
        </tr>

         <tr>
          <td>Start
          
          </td>
          <td><?php echo form::text("startDate", "size = '40' data-date-format='yyyy-mm-dd' class='form-control datepicker-input' ",$data['data1']['startDate']);?></td>  
          

        </tr>

        
         <tr>
          <td>End

          </td>
          <td><?php echo form::text("endDate", "size ='40' data-date-format='yyyy-mm-dd'  class='form-control datepicker-input'",$data['data1']['endDate']);?></td>  
        </tr>



      </table>
      <?php echo form::submit("Submit","class='btn btn-primary'");?>

    




    </div>
  </div>
  </div>





</form>

</body>
 
<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
<script src="<?php echo url::asset('backend/js/highcharts/highcharts.js');?>"></script>
<script src="<?php echo url::asset('backend/js/highcharts/modules/exporting.js');?>"></script>





<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>