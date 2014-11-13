<script type="text/javascript" src='<?php echo url::asset("frontend/js/grapnel.js");?>'></script>
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
      $(".download-folder").fadeIn().html(result);
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
      $(".download-folder").html(result);
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
        <div class="download-folder">
          <ul>
            <li>
              <div class="folder-icon">
              <div id="file-count"><i class="fa fa-user"></i> <span>02</span></div>
              </div>
              <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
              <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <!-- <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>02</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>02</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>102</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>05</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>02</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>10</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li>
            <li>
            <div class="folder-icon">
            <div id="file-count"><i class="fa fa-user"></i> <span>00</span></div>
            </div>
            <div class="folder-name"><a href="#">Lorem Ipsum Dolor</a></div>
            <div class="folder-info">1 Sub-Folder, 2 Fail</div>
            </li> -->
          </ul>
        </div>
        </div>
        <div class="gallery-files" id='latest-file'>
        <div class="download-gallery-heading">Fail Terkini</div>
        <ul>
        <?php
        if($latestFiles):
        foreach($latestFiles as $row):?>
        <li>
          <div class="file-icon">
            <div class="file-icon-wrap"><img src="<?php echo url::asset("frontend/images/pdf_icons.png");?>" width="48" height="63"  alt=""/></div>
          </div>
          <div class="folder-name">
            <a href="#<?php echo "$row[fileFolderID]/$row[fileID]";?>"><?php echo $row['fileName'];?></a>
          </div>
          <div class="folder-info"><?php echo $row['fileType'];?></div>
        </li>

        <?php
        endforeach;
        else:?>
        Tiada fail terkini
        <?php
        endif;
        ?>
         <!-- 
          <li>
            <div class="file-icon">
            <div class="file-icon-wrap"><img src="<?php echo url::asset("frontend/images/pdf_icons.png");?>" width="48" height="63"  alt=""/></div>
            </div>
            <div class="folder-name"><a href="#">Panduan Media Sosial</a></div>
            <div class="folder-info">PDF</div>
          </li>

          <li>
            <div class="file-icon">
            <div class="file-icon-wrap"><img src="images/pdf_icons.png" width="48" height="63"  alt=""/></div>
            </div>
            <div class="folder-name"><a href="#">Agenda Mensyuarat 2014</a></div>
            <div class="folder-info">PDF</div>
          </li>
          <li>
            <div class="file-icon">
            <div class="file-icon-wrap"><img src="images/doc_icon.png" width="48" height="63"  alt=""/></div>
            </div>
            <div class="folder-name"><a href="#">Surat Sokongan Majikan</a></div>
            <div class="folder-info">DOC</div>
          </li>
          <li>
            <div class="file-icon">
              <div class="file-icon-wrap">
                <img src="images/pdf_icons.png" width="48" height="63"  alt=""/>
              </div>
            </div>
            <div class="folder-name"><a href="#">Nota Kelas Komputer En Mazlan</a></div>
            <div class="folder-info">PDF</div>
          </li> -->
        </ul>
        </div>
      </div>
    </div>
  </div>