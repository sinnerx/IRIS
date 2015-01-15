<h3 class='m-b-xs text-black'>
Facebook
</h3>
<div class='well well-sm'>
Facebook Page
</div>
<style type="text/css">
  
.form-control
{
  width:90%;
  display: inline;
}

</style>

<body>

<div class='row'>
  <div class='col-sm-6'>
    <div class='table-responsive'>

<?php if ($pageID == "") { ?>
 <form method='post'  action='<?php echo url::base('facebook/getPageId');?>'>  

    <div class="alert alert-info">      
        <i class="fa fa-info-sign"></i><?php echo $alert ?>
        <?php echo form::submit("Connect","class='btn btn-primary' style=margin-left:30px");?>
    </div>
</form>
<?php } else { ?>

<form  method='post'  action='<?php echo url::base('facebook/getPageId');?>'>
    <div class="alert alert-info">
          Site already link with facebook                   
        <?php echo form::submit("Change Page","class='btn btn-primary'  style=margin-left:300px");?>
      
            
    </div>
</form>


 
  <form method="post">
    <div class='panel panel-default'>
      <div class='panel-body'>
        <table class='table'>
          <tr>
            <td>Page Name</td><td>: <?php echo $data['pageInfo']['name'];?></td>
          </tr>
          <tr>
            <td>Page About</td><td>: <?php echo $data['pageInfo']['about'];?></td>
          </tr>
          <tr>
            <td>Page Description</td><td>: <?php echo $data['pageInfo']['description'];?></td>
          </tr>
          <tr>
            <td>Website</td><td>: <?php echo $data['pageInfo']['website'];?></td>
          </tr>
          <tr>
            <td>Likes</td><td>: <?php echo $data['pageInfo']['likes'];?></td>
          </tr>
        </table>
      </div>
      <div class='panel-footer'>
     <!--   <input type='submit' class='form-control btn btn-primary' />  -->
      </div>
    </div>
  </form>
  

<?php } ?>

      
      
    </div>
  </div>
</div>



</body>
 

