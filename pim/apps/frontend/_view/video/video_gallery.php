 <!-- Video Script -->
        
        <link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/video/css/jquery.jscrollpane.css"); ?>" media="all" />
		<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/video/css/video.css"); ?>" />
		<!--[if lte IE 8 ]><link rel="stylesheet" type="text/css" href="css/ie_below_9.css" /><![endif]-->
        
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/swfobject.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.dotdotdot-1.5.1.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.address.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.easing.1.3.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.mousewheel.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.jscrollpane.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/froogaloop.js"); ?>"></script>
        <script type="text/javascript" src="http://www.youtube.com/player_api"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.apPlaylistManager.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.apYoutubePlayer.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.apVimeoPlayer.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.func.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/new/jquery.videoGallery.min.js"); ?>"></script>
        <script type="text/javascript">
		
			// FLASH EMBED PART
			var flashvars = {};
			var params = {};
			var attributes = {};
			attributes.id = "flashPreview";
			params.quality = "high";
			params.scale = "noscale";
			params.salign = "tl";
			params.wmode = "transparent";
			params.bgcolor = "#111";
			params.devicefont = "false";
			params.allowfullscreen = "true";
			params.allowscriptaccess = "always";
			swfobject.embedSWF("preview.swf", "flashPreview", "100%", "100%", "9.0.0", "expressInstall.swf", flashvars, params, attributes);
			
			//functions called from flash
			var jsReady = false;//for flash/js communication
			function flashVideoEnd() {jQuery.fn.videoGallery.flashVideoEnd();}
			function flashVideoStart() {jQuery.fn.videoGallery.flashVideoStart();}
			function dataUpdateFlash(bl,bt,t,d) {jQuery.fn.videoGallery.dataUpdateFlash(bl,bt,t,d);}
			function flashVideoPause() {jQuery.fn.videoGallery.flashVideoPause();}
			function flashVideoResume() {jQuery.fn.videoGallery.flashVideoResume();}
			function flashMainPreviewOff() {jQuery.fn.videoGallery.flashMainPreviewOff();}
			function flashResizeControls() {jQuery.fn.videoGallery.flashResizeControls();}
			function isReady() {return jsReady;}
			
			jQuery(document).ready(function($) {
				jsReady = true;
			    //init component
			    $('#componentWrapper').videoGallery({	
					
					/* REQUIRED */
					
					/* DEEPLINKING SETTINGS */
				    /* useDeeplink: true, false */
					useDeeplink:false,
					/* startUrl: deeplink start url, enter 'ul' data-address/'li' data-address (two levels). Or just 'ul' data-address (single level). */
					startUrl: 'mix1/youtube_single1',
					
					/* NO DEEPLINKING SETTINGS */
					/*activePlaylist: enter element 'id' attributte */
					activePlaylist:'mix1',
					/*activeItem: video number to start with (-1 = none, 0 = first, 1 = second, 2 = third ...etc) */
					activeItem:0,
					
					/* GENERAL */
					/*thumbOrientation: horizontal, vertical (for scrolling) */
					thumbOrientation: 'vertical',
					/*playlistPosition: bottom / right */
					playlistPosition: 'right',
					/*fullSize: true/false (dont forget to edit the css as well) */
					fullSize: false,
					/*flashHolder: id of the flash movie */
					flashHolder:'#flashPreview',
					
					/* DEFAULTS */
					
					/*defaultVolume: 0-1 */
					defaultVolume:0.5,
					/*autoPlay: true/false */
					autoPlay:true,
					/* loopingOn: loop playlist on end (last item in playlist), true/false */
					loopingOn:true,
					/* randomPlay: random play in playlist, true/false */
					randomPlay:false,
					/*autoAdvanceToNextVideo: true/false */
					autoAdvanceToNextVideo:true,
					/*autoMakePlaylistThumb: true/false (valid only for youtube and vimeo, auto make thumb for each video) */
					autoMakePlaylistThumb:true,
					/*autoMakePlaylistInfo: true/false (valid only for youtube and vimeo, auto make title and description for each video) */
					autoMakePlaylistInfo:true,
					/* outputPlaylistData: console.log out playlist data */
					outputPlaylistData:false,
					/* useYoutubeHighestQuality: true/false (use highest available quality for youtube video, if false, then it set to default)  */
					useYoutubeHighestQuality:false,
					
					videoGallerySetupDone:function() {
						//console.log('videoGallerySetupDone');
					}
				});		
    	    });
		
        </script>
	
    
    <!-- End Video Script -->

<div class="body-container clearfix">

<div class="lft-container">

<?php
    $breadcrumb = Array();
    $breadcrumb[] = Array("Galeri Video",url::base(request::named("site-slug")."/video/"));
    $breadcrumb[] = Array($album['videoAlbumSlug']);
?>

<h3 class="block-heading"><?php echo model::load("template/frontend")->buildBreadCrumbs($breadcrumb); ?></h3>

<div class="block-content clearfix">

<div class="page-content">


                              <?php if(count($video_rows)>0){ ?>

<div class="page-sub-wrapper video-page clearfix">

      <div id="componentWrapper">
  
			 <!-- inner wrapper for the whole player -->
    		 <div class="componentInnerWrapper">

                 <!-- holds player -->
                 <div class="playerHolder">
                 
                     <!-- video holders -->
                     <div class="mediaHolder"></div>
                     
                     <!-- flash embed part --> 
                     <div id="flashPreview">
                         <a href="http://www.adobe.com/go/getflashplayer">
                            <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
                         </a>
                     </div>
                     
                     <!-- preview image --> 
                 	 <div class="mediaPreview"></div>
                     
                     <div class="playerControls">
                 
                          <div class="player_playControl"><img src='<?php echo url::asset("frontend/video/data/icons/play.png"); ?>' width='12' height='14' alt=''/></div>
                            
                          <div class="player_progress">
                              <!-- seekbar background -->
                              <div class="progress_bg"></div>
                              <div class="load_level"></div>
                              <div class="progress_level"></div>
                          </div>
                         
                          <div class="player_mediaTime">00:00 | 00:00</div>
                          
                          <div class="player_volume"><img src='<?php echo url::asset("frontend/video/data/icons/volume.png"); ?>' width='13' height='14' alt=''/></div>
                          <div class="volume_seekbar">
                             <!-- volume background -->
                             <div class="volume_bg"></div>
                             <div class="volume_level"></div>
                          </div>
                          
                          <div class="player_fullscreen"><img src='<?php echo url::asset("frontend/video/data/icons/fullscreen_enter.png"); ?>' width='12' height='12' alt=''/></div>
                          
                          <div class="player_volume_tooltip">
                              <div class="player_volume_tooltip_value">0</div>
                          </div>
                          
                          <div class="player_progress_tooltip">
                              <div class="player_progress_tooltip_value">0:00 | 0:00</div>
                          </div>
                          
                     </div>
                     
                     
                     <div class="youtubeHolder"></div>	
                     <div class="vimeoHolder"></div>
                     
                     <div class="mediaPreloader"></div>
                     
                     <div class="bigPlay"><img src='<?php echo url::asset("frontend/video/data/icons/big_play.png"); ?>' width='76' height='76' alt=''/></div>
                 </div>
                   
                 <!-- holds playlist -->  
                 <div class="playlistHolder">
                
                     <div class="componentPlaylist">
                     
                         <!-- playlist_inner: container for scroll -->
                         <div class="playlist_inner">
                         
                      
                              <ul id='mix1' data-address="mix1"> 
                                <?php foreach ($video_rows as $row) { ?>
                                <li data-title="<?php echo $row['videoName'] ?>" data-address="youtube_single1" data-type='youtube_single' data-path="http://gdata.youtube.com/feeds/api/videos/<?php echo $row['videoRefID']; ?>?v=2&amp;format=5&amp;alt=jsonc" ></li>
                                <?php } ?>
                              </ul>
                             
                           
                       
                         </div>
                     </div>
                 </div>
             </div>  
        </div>
        
        
       
           
        
        
      
      
      
      
</div>
                              <?php }else{ ?>
<div class="page-description"> 
No video uploaded.


</div>
                              <?php } ?>


 <div class="video-info">
 
 <h3 class="video-info-heading"><?php echo $album['videoAlbumName']; ?></h3>
      
      <?php echo $album['videoAlbumDescription']; ?>
      
      </div>


<div class="clr"></div>








</div>




</div>


</div>



</div>