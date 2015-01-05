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
        <th>Views</th>
       
      </tr>
     
      <?php 

      $no = 1;
      foreach($data['data3'] as $row)
      {   
       ?>
      <tr <?php echo $opacity;?>>
        <td><?php echo $no++;?></td>
        <td><?php echo $row['page'];?></td>
        <td><?php echo $row['views'];?></td>
       
        <td>
          <center>
       
          <a href="<?php echo url::base('ajax/googleanalytics/detail'); ?>?page=<?php echo $row['page']; ?>&start=<?php echo $data['data1']['startDate']; ?>&end=<?php echo $data['data1']['endDate']; ?>" data-toggle="ajaxModal">
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
          <td width="150px">Test
          <?php echo flash::data("siteSlug");?>      
          </td>
          <td><?php echo form::text("siteSlug","size='40' $disabled class='form-control'",$data['data1']['row']['siteSlug']);?></td>  
        </tr>

         <tr>
          <td width="150px">Start
          
          </td>
          <td><?php echo form::text("startDate", "size = '40' data-date-format='yyyy-mm-dd' class='form-control datepicker-input' ",$data['data1']['startDate']);?></td>  
          

        </tr>

        
         <tr>
          <td width="150px">End

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
 
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>





<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>