<script type="text/javascript" src='<?php echo url::asset("frontend/js/grapnel.js");?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/fileicon.css");?>">
<script type="text/javascript">
  
var file = new function($)
{
  this.base_url   = "<?php echo url::base('{site-slug}');?>/";
  this.currFolder = 0;
  this.openFolder = function(folder)
  {
    $("#latest-file").hide();
    $(".page-description").show();

    // $(".page-description").show();
    var folder = folder?folder:this.currFolder;
    this.currFolder = folder;
    var url = this.base_url+"ajax/file/openFolder/"+folder;
    $.ajax({type:"GET",url:url}).done(function(result)
    {
      $(".gallery-folder").fadeIn().html(result);
      if(file.currFolder == 0)
      {
        $("#latest-file").show();
      }
    });
  }

  this.openFile = function(folder,file)
  {
    $(".page-description").hide();
    $("#latest-file").hide();
    //hide page-desc
    // $(".page-description").hide();
    $.ajax({type:"GET",url:this.base_url+"ajax/file/openFile/"+file}).done(function(result)
    {
      $(".gallery-folder").html(result);
    });
  }
}(jQuery);

var router = new Grapnel;
jQuery(document).ready(function()
{
  router.get(":id",function(req)
  {
    file.openFolder(req.params.id);
  });

  router.get(":id/:file",function(req)
  {
    file.openFile(req.params.id,req.params.file)
  });

});

if(!window.location.hash)
{
  window.location.hash  = "0";
}

</script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/download_gallery.css");?>">
<h3 class="block-heading">
<?php
echo model::load("template/frontend")
->buildBreadCrumbs(Array(
      Array("Galeri Muat Turun",url::createByRoute("file-index",null,true))
            ));
?>
</h3>
  <div class="block-content clearfix">
    <div class="page-content">
      <div class="page-description">
      Fail-fail yang membolehkan anda memuat turun.
      </div>
      <div class="page-download">
      <div class="gallery-folder">
        </div>
        <div class="gallery-files" id='latest-file'>
        <div class="download-gallery-heading">Fail Terkini</div>
        <ul>
        <?php
        if($latestFiles):
        foreach($latestFiles as $row):?>
        <li>
          <div class="file-icon">
            <div class="file-icon-wrap">
              <div class="xfile-icon xfile-icon-lg" data-type="<?php echo $row['fileExt'];?>" style="margin-top:10px;"></div>
            </div>
          </div>
          <div class="folder-name">
            <a href="#<?php echo "$row[fileFolderID]/$row[fileID]";?>"><?php echo $row['fileName'];?></a>
          </div>
          <div class="folder-info"><?php echo $types[$row['fileExt']];?></div>
        </li>

        <?php
        endforeach;
        else:?>
        Tiada fail terkini
        <?php
        endif;
        ?>
        </ul>
        </div>
      </div>
    </div>
  </div>