 <!-- Video Script Youtube 3 Api -->
        
        <link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/video/css/api3.1/jquery.jscrollpane.css"); ?>" media="all" />
  
        
    <link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/video/css/api3.1/videoGallery_buttons.css"); ?>">
    <!--[if lte IE 8 ]><link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/video/css/api3.1/ie_below_9.css"); ?>" /><![endif]-->
        
        
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/jquery.address.js"); ?>"></script><!-- deeplink -->
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/jquery.mousewheel.min.js"); ?>"></script><!-- scroll -->
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/jquery.jscrollpane.min.js"); ?>"></script><!-- scroll -->
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/froogaloop2.min.js"); ?>"></script><!-- vimeo -->
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/jquery.dotdotdot-1.5.1.js"); ?>"></script><!-- description shortener -->
        <script type="text/javascript" src="<?php echo url::asset("frontend/video/js/api3.1/jquery.aprvp.min.js"); ?>"></script>
        <script type="text/javascript">
        
      var hap_players = [];    
      jQuery(document).ready(function($) {
        
        /* SETTINGS */
        var rvp_settings = {
          /* mediaId: unique string for player identification (if multiple player instances were used, then strings need to be different!) */
          mediaId:'player1',
          /* useDeeplink: true, false */
          useDeeplink:false,
          /*activePlaylist: Active playlist to start with. If no deeplink is used, enter element 'id' attribute, or if deeplink is used enter (data-address) deeplink string like 'playlist1'.  */
          activePlaylist:'playlist11a',
          /*activeItem: Active video to start with. Enter number, -1 = no video loaded, 0 = first video, 1 = second video etc */
          activeItem:0,
          /* GENERAL SETTINGS */
          /*defaultVolume: 0-1 */
          defaultVolume:0.5,
          /*autoPlay: true/false (defaults to false on mobile)*/
          autoPlay:true,
          /*randomPlay: true/false */
          randomPlay:false,
          /* loopingOn: on playlist end rewind to beginning (last item in playlist) */
          loopingOn: true,
          /*autoAdvanceToNextVideo: true/false (use this to loop video) */
          autoAdvanceToNextVideo:true,
          /*autoOpenDescription: true/false  */
          autoOpenDescription:false,
          usePlaylist:true,
          useControls:true,
          /*autoHideControls: auto hide player controls on mouse out: true/false. Defaults to false on mobile. */
          autoHideControls:false,
          /*controlsTimeout: time after which controls and playlist hides in fullscreen if screen is inactive, in miliseconds. */
          controlsTimeout:3000,
          /*playlistOrientation: vertical/horizontal  */
          playlistOrientation:'vertical',
          /*scrollType: scroll/buttons  */
          scrollType:'buttons',
          
          /* YOUTUBE SETTINGS */
          ytAppId:'AIzaSyAaGolNQ4zfeAuxF7b_02sHHFvNs3Hs-1o',/* youtube api key: https://developers.google.com/youtube/registering_an_application */
          ytTheme:'dark',
          ytShowinfo:true,
          
          /*playlistList: dom element which holds list of playlists */
          playlistList:'#playlist_list',
          
          /* showControlsInAdvert: true/false (show controls while video advert plays)  */
          showControlsInAdvert:true,
          /* disableSeekbarInAdvert: true/false (disable seekbar while video advert plays)  */
          disableSeekbarInAdvert:true,
          /* showSkipButtonInAdvert: true/false (show skip button while video advert plays)  */
          showSkipButtonInAdvert:true,
          advertSkipBtnText:'SKIP AD >',
          advertSkipVideoText:'You can skip to video in',
          
          logoPath: '',
            logoPosition: 'tl',/* tl, tr, bl, br */
            logoXOffset: 5,
            logoYOffset: 5,
            logoUrl: '',
            logoTarget: '_blank',
          
          useShare: false,
          /*fsAppId: facebook application id (if you use facebook share, https://developers.facebook.com/apps) */
          fsAppId:''
        };
        
        hap_players[0] = $('#mainWrapper').aprvp(rvp_settings);
        initDemo($);
      });
      
      
        </script>
    
 <!-- End YT3 API video Script-->
 <!-- Video Script -->
    
     
        <script type="text/javascript">
		
		
			
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
<!-- start componentWrapper -->
   
      
      
  <!-- end componentWrapper-->    
   
        
        
  <!-- start mainwrapper -->

   <div id="mainWrapper"></div>
        
        <div id="playlist_list">

            
             
             
           
              <?php      
                 $videoref = array();
                 foreach ($video_rows as $row) { 
                 $videoref[] = $row['videoRefID'];
                  }
                
              ?>

              
              
              <div id='playlist11a' data-address="playlist11a">
                
                 <div class="playlistNonSelected" data-address="youtube_single" data-type="youtube_single_list" data-path="<?php echo rtrim(implode(',',  $videoref), ',');
?>"></div>
              </div>
              
              </div>
        

  <!-- end mainwrapper-->
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

          <div id="comment-container">
            <?php controller::load("comment","getComments/".$album['videoAlbumID']."/video_album");?>
          </div>
            <?php if(session::get("userID")){controller::load("comment","getForm");} ?>