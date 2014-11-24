<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Home</title>
<link href="<?php echo url::asset("frontend_facade/css/styles.css");?>" rel="stylesheet" type="text/css">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/jquery.mCustomScrollbar.css");?>" rel="stylesheet" />
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="<?php echo url::asset("frontend_facade/js/javascript.js");?>" type="text/javascript"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>  
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
    <script src="<?php echo url::asset("frontend/js/jquery.ticker.js");?>" type="text/javascript"></script>
    <script src="<?php echo url::asset("frontend/js/site.js");?>" type="text/javascript"></script>  
<script>
var $c = jQuery.noConflict();
    $c(function() {  
        $c("#accordion").accordion({
			active: false,
		collapsible: true	
		});  
    });  
</script>
</head>
<body>
<div id="toppanel">
    <div id="panel">
    <div class="wrap"><div class="panel_button" id="hide_button_top" style="display: none;"> <a href="#"><i class="fa fa-times-circle-o"></i> Close</a> </div></div>
      <div id="panel_contents">
        <?php
      foreach($stateR as $stateID => $stateName)
      {
        ## is not sabah.
        if($stateID != 12)
        {
          if(isset($res_site[$stateID]))
          {
            echo "<div class='col-top'>";
            echo "<h3>$stateName</h3>";
            echo "<ul>";
            foreach($res_site[$stateID] as $row)
            {
              $url  = url::base($row['siteSlug']);
              $name = $row['siteName'];
              echo "<li><a href='$url'>$name</a></li>";
            }
            echo "</ul>";
            echo "</div>";
          }
        }
          }

          ## sabah part.
          if(isset($res_site[12]))
          {
            echo "<div class='col-large'>";
          echo "<h3>Sabah</h3>";

          ## devide by 5 column.
          $totalPerColumn = ceil(count($res_site[12])/5);

          $ULopened = false;
          $currNo   = 1;
          foreach($res_site[12] as $row)
          {
            if(!$ULopened)
            {
              $ULopened = true;
              echo "<ul>";
            }
            $url  = url::base($row['siteSlug']);
            echo "<li><a href='$url'>".$row['siteName']."</a></li>";

            if($currNo == $totalPerColumn)
            {
              echo "</ul>";
              $ULopened = false;
              continue;
            }
            $currNo++;
          }
          if($ULopened)
          {
            echo "</ul>";
            $ULopened = false;
          }

          echo "</div>";## end of col-large
          }

          ?>
      </div>
    </div>
    <div class="panel-wrap">
    <div class="panel_button" style="display: visible;" id="show_button"> <a href="#">Ke Pi1M Lain <i class="fa fa-angle-down"></i></a> </div>
    <div class="panel_button" id="hide_button" style="display: none;"> <a href="#">Ke Pi1M Lain <i class="fa fa-angle-up"></i></a> </div>
  </div>
  </div>
<div class="main-wrap">
<div class="top-header">
<div class="wrap clearfix">
<div class="user-setting">
<a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>" class="rgstr-button">Login</a>
<a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>" class="rgstr-button">Register</a>
</div>
</div>
</div>
<div class="header">
<div class="wrap">
<div class="logo">
<h1><a href='<?php echo url::base("{site-slug}");?>' style='color:inherit;'><?php echo $siteName;?></a></h1>
</div>
<div class="celcom-top"></div>
</div>
</div>
   <div id="nav" class="clearfix">
           <div class="navigation">
<ul class="nav clearfix">
  <?php
    $componentChildR  = model::load("site/menu")->componentChild();
    $mobileMenu     = "";

    if($menuR)
    {
      ## prepare list of menu under component.
      $componentMenu  = Array(
              1=>Array("page@index"),
              2=>Array("main@index"),
              3=>Array("activity@index","activity@index","activity@view"),
              4=>Array("gallery","gallery","gallery","forum","video","member"),
              5=>Array("main@contact"),
              6=>Array("blog@article","blog@view")
                  );

      $menuNo = 0;
      foreach($menuR as $row)
      {
        $menuNo++;

        ## custom hardcoded top menu.
        if($menuNo == count($menuR))
        {
          $faqHref  = url::base("{site-slug}/soalan-lazim");
          $cssActive  = controller::getCurrentMethod() == "faq"?"active":"";
          echo "<li><a href='$faqHref' class='$cssActive'>Soalan Lazim</a></li>";
        }

        ## if component status deactivated, skip.
        if($row['componentStatus'] == 0)
        {
          continue;
        }

        $component  = $row['componentNo'];
        $menuName = !$row['componentName']?$row['menuName']:$row['componentName'];
        $main_url = url::base(request::named("site-slug")."/".$row['componentRoute']);
        $pageID   = null;

        ## skip certain component for not user.
        if(in_array($component,Array(4)) && !session::has("userID"))
          continue;

        ## pages
        if($component == 1)
        {
          $page = model::load("page/page");

          ## get default.. (like name or slug, can be used if tyoe = 1[default])
          $defaultR = $page->getDefault();
          $pageID = $row['menuRefID'];

          ## get page.
          $row_page = $page->getPage($pageID);

          $defaultType  = $row_page['pageDefaultType'];

          ## if type 1, use default slug.
          $main_url   = $row_page['pageType'] == 1?url::base(request::named('site-slug')."/".$defaultR[$defaultType]['pageDefaultSlug']):$main_url;
          $menuName   = $row_page['pageType'] == 1?$defaultR[$defaultType]['pageDefaultName']:$row_page['pageName'];

          ## check children page.
          $childPage  = $page->getChildrenPage($pageID);

          if($childPage)
          {
            foreach($childPage as $row)
            {
              $childDefaultType = $row['pageDefaultType'];

              ## get child page url.

              ## default check on $defaultR.
              $childPageURL = $main_url."/".($row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultSlug']:$row['pageSlug']);
              $pageName   = $row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultName']:$row['pageName'];

              ## childpage R
              $childPageR[$pageID][]  = Array($childPageURL,$pageName);
            }
          }
        }
        
        ## end page component.

        ## echo the menu.
        $controller = controller::getCurrentController();
        $method   = controller::getCurrentMethod();

        ## prepare the active menu css.
        $cm = $controller."@".$method;

        $cssActive  = "";
        if(isset($componentMenu[$component]) && (in_array($cm,$componentMenu[$component]) || in_array($controller,$componentMenu[$component])))
          $cssActive  = "active";

        $dropdownIcon = isset($childPageR[$pageID]) || isset($componentChildR[$component])?'<span><i class="fa fa-sort-asc"></i></span>':"";
        echo "<li><a href='$main_url' class='$cssActive'>$menuName $dropdownIcon</a>";

        ## mobileMenu
        $mobileMenu .= "<li><a href='$main_url'>$menuName</a></li>";

        ## for first appearance in childmenu. github #10
        $firstmenu  = "<li><a href='".$main_url."'>$menuName</a></li>";
        if($component == 1)
        {
          if(isset($childPageR[$pageID]))
          {
            $mobileMenu .= "<ul>";

            echo "<div>";
            echo '<div class="nav-column"><div class="menu-block">';
            echo "<ul class='clearfix'>";
            echo "<h3>$menuName</h3>";

            $no = 1;
            foreach($childPageR[$pageID] as $row)
            {
              if($no == 1)
              {
                echo $firstmenu;
              }
              echo "<li><a href='".$row[0]."'>$row[1]</a></li>";

              $mobileMenu .= "<li><a href='$row[0]'>$row[1]</a></li>";

              $no++;
            }

            echo "</ul>";
            echo "</div></div>";
            echo "</div>";

            $mobileMenu .= "</ul>";
          }
        }


        if(isset($componentChildR[$component]))
        {
          $mobileMenu .= "<ul>";

          $columnlimit  = ceil(count($componentChildR[$component]) / 2);

          echo "<div>";
          #echo '<div class="nav-column">';
          
          $no = 1;
          $colno = 1;
          $blockno  = 1;
          foreach($componentChildR[$component] as $headername=>$rowR)
          {
            if(!isset($submenuBuffer[$colno]))
            {
              $submenuBuffer[$colno] = "";
            }

            $submenuBuffer[$colno] .= "<div class='menu-block'>";
            $submenuBuffer[$colno] .= "<ul class='clearfix'>";

            $mobileMenu .= "<div>";
            $mobileMenu .= "<li class='submenu-heading'>".$headername."</li>";

            $submenuBuffer[$colno] .= "<h3>$headername</h3>";
            foreach($rowR as $row)
            {
              $href = $row[1] == "#"?"#":url::base("{site-slug}/".$row[1]);
              $submenuBuffer[$colno] .= "<li><a href='$href'>$row[0]</a></li>";

              $mobileMenu .= "<li><a href='$href'>$row[0]</a></li>";
              $no++;
            }

            $submenuBuffer[$colno] .= "</ul>";
            $submenuBuffer[$colno] .= "</div>";

            if($blockno >= $columnlimit)
            {
              $colno++;
            }

            $blockno++;
          }

          ## print the buffer.
          foreach($submenuBuffer as $colno=>$buffer)
          {
            echo "<div class='nav-column'>";
            echo $buffer;
            echo "</div>";
          }


          $mobileMenu .= "</ul>";
          
          #echo "</div>";
          echo "</div>";
        }


        echo "</li>";
      }
    }

    ?>
	<div class="search-top">
    <form method='get' action='<?php echo url::base("{site-slug}/carian");?>'>
        <input type="text" class="search-top-input" name='q' id='q' value="<?php echo request::get('q');?>" placeholder='Carian'>
      </form>
  </div>
	</ul>
</div>
    </div>
<div class="main-container">
<div class="wrap">
<div class="body-container clearfix">
<div class="lft-container">
  <div class="slideshow">
    <?php
    #========================================
    # SECTION : SLIDESHOW START
    #========================================
    ?>
    <style type="text/css">

    </style>
    <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider">
          <?php
          foreach($res_slider as $row):
            $url  = url::asset("frontend/images/slider/".$row['siteSliderImage']);
                $href   = $row['siteSliderLink'];
            ?>
          <a href='<?php echo $href;?>' target='_blank'><img src='<?php echo $url;?>' data-thumb='<?php echo $url;?>' alt='' title='<?php echo $row['siteSliderName'];?>' /></a>
          <?php endforeach;
          ?>
        </div>
    </div>
    <?php
    #========================================
    # SECTION : SLIDESHOW END
    #========================================
    ?>
  </div>
  <?php echo template::showContent();?>
</div>
<div class="rght-container">
<div class="blueBox calendar-container">
                <div class="blueBoxContent1">
                 <span class="activity-top"><i class="fa fa-calendar"></i> Kalendar Aktiviti</span>
                 <?php controller::load("partial","calendar");?>
               <!-- <div id="calendar-rght">
                  <div class="date">24</div>
                  <div class="month clearfix">
                  <div class="month-prev"></div>
                  <div class="month-now">September</div>
                  <div class="month-next"></div>
                  </div>
                  <div class="cal-date clearfix">
                  <ul>
                  <li class="disable">29</li>
                  <li class="disable">30</li>
                  <li class="disable">31</li>
                  <li>1</li>
                  <li class="today">2</li>
                  <li>3</li>
                  <li>4</li>
                  </ul>
                  <ul>
                  <li>5</li>
                  <li>6</li>
                  <li>7</li>
                  <li>8</li>
                  <li>9</li>
                  <li>10</li>
                  <li>11</li>
                  </ul>
                  <ul>
                  <li>12</li>
                  <li>13</li>
                  <li>14</li>
                  <li>15</li>
                  <li>16</li>
                  <li>17</li>
                  <li>18</li>
                  </ul>
                  <ul>
                  <li>19</li>
                  <li>20</li>
                  <li>21</li>
                  <li>22</li>
                  <li>23</li>
                  <li>24</li>
                  <li>25</li>
                  </ul>
                  <ul>
                  <li>26</li>
                  <li>27</li>
                  <li>28</li>
                  <li>29</li>
                  <li>30</li>
                  <li>31</li>
                  <li class="disable">1</li>
                  </ul>
                  </div>
                  </div>  -->
                </div>
            <div class="blueBoxRibbon"></div>
        	</div>
<div class="blueBox">
            <span class="activity"><i class="fa fa-clock-o"></i>Waktu Operasi</span>
                <div class="blueBoxContent1-right">
                <div class="blueBoxContent">
                    <strong>Dibuka setiap hari</strong>
                    <div class="bluehilite">
                      <?php if(in_array(authData("current_site.stateID"),Array(12,13))):?>
                          8.00 pagi - 5.00 petang
                        <?php else:?>
                        9.00 pagi - 6.00 petang
                        <?php endif;?>
                    </div>
                    (kecuali cuti umum)<br><br>
                    <strong>Yuran keahlian:</strong><br> 
                    <div class="bluehilite">RM5.00</div>
                    (tertakluk kepada terma dan syarat)<br><br>
                    <strong>Caj Penggunaan:</strong><br> 
                    <div class="bluehilite">RM1.00 sejam (ahli)<br>
                    RM2.00 sejam (bukan ahli)</div> 
                </div>
                </div>
            <div class="blueBoxRibbon"></div>
        	</div>
            <div class="blueBox">
            <span class="activity"><i class="fa fa-print"></i> Perkhidmatan Lain</span>
                <div class="blueBoxContent1-right">
                <div class="blueBoxContent">
                    <strong>Pencetak dan Fotokopi (hitam/putih)</strong><br>
                    <div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>
                    <strong>Pencetak dan Fotokopi (warna)</strong><br>
                    <div class="bluehilite">RM1.00</div> setiap mukasurat<br><br>
                    <strong>Pengimbas</strong><br>
                    <div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>
                    </div>
                </div>
            <div class="blueBoxRibbon"></div>
        	</div>
<div class="normalBox">
            <h1>Capaian Pantas</h1>
            <a href="#">Universal Service Provision</a><br> 
            <a href="#">Suruhanjaya Komunikasi dan Multimedia</a><br>
            <a href="#">Kementerian Komunikasi dan Multimedia</a><br>
            <a href="#">Service Provider Website</a><br>
            <a href="#">Other Related Website</a> 
           	</div> 
</div>
<div class="clr"></div>
<div class="bttm-center">
<div class="announcement clearfix">
<?php 
## set announcement only for site landing page. (main/index)
if(controller::getCurrentController() == "main" && controller::getCurrentMethod() == "index"):

  $annList = model::load("site/announcement")->getAnnouncementList($row_site['siteID'],true);
if($annList){?>
<div class="label-anncmnt">Pengumuman</div>
<div class="cntnt-anncmnt">
<ul id="js-news" class="js-hidden">
        <?php
          foreach($annList as $row)
          {
              /*if(strpos($row['announcementLink'], 'localhost') !== false || strpos($row['announcementLink'], 'p1m') !== false){
                 $target = '';
              }else{
                   $target = "target='_blank'";
              }
              if($row['announcementLink'] != ""){
                  $href = "href='".$row['announcementLink']."' class='announcement-linked'";
              }else{
                  $href = "href='#'  class='announcement-unlinked'";
              }*/
              if($row['announcementLink'] != "http://")
              {
                $attr = "class='announcement-linked' ";
                $attr .= "href='".$row['announcementLink']."' ";
                $attr .= "target='_blank'";
              }
              else
              {
                $attr = "class='announcement-unlinked'";
                $attr .= "href='#'";
              }

              echo "<li><a $attr>".$row['announcementText']."</a></li>";
          }
          ?>
</ul>

</div>
<?php }?>
<?php endif;## end main/index check. ?>
</div>
</div>
<div class="bttm-down clearfix">
<div class="bttm-1">
<div class="maps-bottom">
<div class="maps-container">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1982.6780417481739!2d99.76039763995057!3d6.347917847778613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304c78508590bd0f%3A0xa081e5cf738400d2!2sKampung+Bukit+Tangga!5e0!3m2!1sen!2s!4v1394689646969" width="390" height="200" frameborder="0" style="border:0"></iframe>
</div>
<div class="maps-label"></div>
</div>
</div>
<div class="bttm-2"> 
<div class="news-bottom">
<h3 class="bottom-heading">Berita Terkini</h3>
<div class="bottom-content">
    <ul>
      <?php if($articles): ?>
      <?php foreach($articles as $article): 
      $url  = model::load("helper")->buildDateBasedUrl($article['articleSlug'],$article['articlePublishedDate'],url::base($article['siteSlug']."/blog"));
      ?>
      <li>
        <a href="<?php echo $url;?>">
        <div class='news-title'><?php echo $article['articleName']; ?></div>
        <div class="news-info"><?php echo date("F j Y",strtotime($article['articlePublishedDate']));?><!-- October 31 2013, 8:00 AM --></div>
        </a>
      </li>
      <?php endforeach; ?>
      <?php else: ?>
        <div class="no-result">- Tiada artikel terkini - </div>
      <?php endif; ?>
    </ul>
</div>
</div>
 </div>
<div class="bttm-3 foto-wrapper">
<h3 class="bottom-heading">Foto Terkini</h3>
<div class="bottom-content foto-bottom">
  <?php if($latestPhotoLink):?>
    <a href='<?php echo $latestPhotoLink;?>'>
      <img src="<?php echo $latestPhotoUrl;?>" width="270" height="208"  alt=""/>
    </a>
  <?php else:?>
  <div class="no-result">- Tiada foto terkini - </div>
  <?php endif;?>
</div>
</div>
</div>
</div>
</div>
<div class="footer">
<div class="wrap clearfix">
<div class="copyright">
Hakcipta Terpelihara © 2013 <a href="#">Pusat Internet 1 Malaysia</a>. All Rights Reserved
<ul class="clearfix">
<li><a href="#">Utama</a></li>   <li> <a href="#">  Mengenai Kami</a>   </li>  <li>  <a href="#">   Hubungi Kami</a></li><li>  <a href="#">   Pautan</a></li>
</ul>
</div>
</div>
</div>
</div>
<!-- Google CDN jQuery with fallback to local -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/minified/jquery-1.9.1.min.js"%3E%3C/script%3E'))</script>
	<!-- custom scrollbars plugin -->
	<script src="<?php echo url::asset("_landing/js/jquery.mCustomScrollbar.concat.min.js");?>"></script>
	<script>
		(function($){
			$(window).load(function(){
			
				$("#content_7").mCustomScrollbar({
					scrollButtons:{
						enable:true
					},
					 advanced:{  
        updateOnBrowserResize:true,   
        updateOnContentResize:true   
      },
					theme:"light-thin"
					
				});
			
			});
		})(jQuery);
	</script>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->

 <!--<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>-->
    <script type="text/javascript" src="<?php echo url::asset("frontend/js/jquery.nivo.slider.js");?>"></script>
    <script type="text/javascript">
	var $s = jQuery.noConflict();
    $s(window).load(function() {
    $s('#slider').nivoSlider();
		
    });
    </script>
    <!-- jQuery if needed -->
		<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
		<script type="text/javascript">
			function DropDown(el) {
				this.dd = el;
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						event.stopPropagation();
					});	
				}
			}

			$(function() {

				var dd = new DropDown( $('#dd') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-2').removeClass('active');
				});

			});

		</script>
    </body>
</html>