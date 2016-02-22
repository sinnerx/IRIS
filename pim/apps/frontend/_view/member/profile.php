<style type="text/css">
    
.resp-tabs-container img
{
    max-width:100%;
}

</style>
<script src="<?php echo url::asset("_templates/js/easyResponsiveTabs.js");?>" type="text/javascript"></script>
<script src="<?php echo url::asset("_scale/js/slimscroll/jquery.slimscroll.min.js");?>" type="text/javascript"></script>
<script src="<?php echo url::asset("_scale/js/bootstrap.js");?>" type="text/javascript"></script>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
<!-- <link rel="stylesheet" href="<?php //echo url::asset("_scale/js/nestable/nestable.css");?>"> -->
 <link rel="stylesheet" href="<?php echo url::asset("_scale/css/bootstrap.css");?>">
 <link rel="stylesheet" href="<?php echo url::asset("_scale/css/app.css");?>">

<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript">
$(document).ready(function(){
    
    // $('#horizontalTab ul').tabs({
    //     select: function(event) {
    //         // You need Firebug or the developer tools on your browser open to see these
    //         console.log(event);
    //         // This will get you the index of the tab you selected
    //         console.log(event.options.selected);
    //         // And this will get you it's name
    //         console.log(event.tab.text);
            
    //     }
    // }); 
    // $("#takequiz").on("click",function(e){
    //     alert('takequiz');
    //     e.preventDefault();
    //     $.post("../lms/quizs/login",function(data) {
    //       //$("#someContainer").html(data);
    //     });
    // });
      // $(function() {
      //   $( "#accordion-kursus" ).accordion();
      // });    
});

</script>
<h3 class="block-heading"><a href='.'>PROFIL AHLI</a> <span class="subforum"> > Pemegang kad</span> 

<?php if($ownPage):?>
    <a href='<?php echo url::base("{site-slug}/profile/edit");?>' class='pull-right'><img src='<?php echo url::asset("frontend/images/edit-button.png");?>' /></a>
    <!-- <a href='<?php echo url::base("{site-slug}/profile/edit");?>' class='fa fa-edit pull-right' style='color:#0062a1;opacity:0.5;'></a> -->
<?php endif;?>
</h3>
<div class="block-content clearfix">
    <div class="page-content">
        <div class="page-description"> 
        </div>
        <div class="page-sub-wrapper profile-page clearfix">
            <div class="lft-sidebar">
            <?php
            $photoUrl   = model::load("image/services")->getPhotoUrl($row['userProfileAvatarPhoto']);
            ?>
            <div class="profile-avatar-member"><img src="<?php echo $photoUrl;?>"></div>
                <div class="profile-user-activity">
                <h3>Aktiviti</h3>
                    <div class="profile-activity-list">
                        <ul>
                            <?php
                            if($activities)
                            {
                                echo "<ul>";
                                foreach($activities as $row_ua):
                                $date   = dateRangeViewer($row_ua['row']['userActivityCreatedDate'],1,"my");
                                $href   = url::createByRoute("api-redirect-useractivity",Array("type"=>$row_ua['row']['userActivityType'],"userActivityID"=>$row_ua['row']['userActivityID']),true);
                                    ?>
                                <li>
                                    <a href='<?php echo $href;?>'><?php echo $row_ua['text'];?></a>
                                    <span><?php echo $date;?></span>                                    
                                </li>
                                <?php
                                endforeach;
                                echo "</ul>";
                            }
                            else
                            {?>
                            <?php
                            }

                            ?>

                        
                    </div>
                </div>
            </div>
            <div class="rght-sidebar">
                <div class="profile-user-info">
                <h3><?php echo $row['userProfileFullName'];?>
                </h3>
                <span class="profile-user-occupation"><?php echo $row['userProfileOccupation']?:"-";?></span>
                <span class="profile-user-location" style='position:relative;top:8px;'><span><?php echo $siteName;?></span><span><?php if($row['userEmail'] != ""):?><a href="mailto:<?php echo $row['userEmail'];?>"><i class="fa fa-envelope"></i></a><?php endif;?></span></span>
                <div class="profile-social-media">
                <ul>
                    <?php 
                    if($row['userProfileFacebook']):?>
                        <li><a target="_blank" href="<?php echo url::route("api-redirect-link",null,true)."?link=".$row['userProfileFacebook'];?>"><i class="fa fa-facebook"></i></a></li>
                    <?php endif;
                    if($row['userProfileTwitter']):?>
                        <li><a target="_blank" href="<?php echo url::route("api-redirect-link",null,true)."?link=".$row['userProfileTwitter'];?>" class="light-blue"><i class="fa fa-twitter"></i></a></li>
                    <?php endif;
                    if($row['userProfileWeb']):?>
                        <li><a target="_blank" href="<?php echo url::route("api-redirect-link",null,true)."?link=".$row['userProfileWeb'];?>"><i class="fa fa-link"></i></a></li>
                    <?php endif;
                    if($row['userProfileEcommerce']):?>
                        <li><a target="_blank" href="<?php echo url::route("api-redirect-link",null,true)."?link=".$row['userProfileEcommerce'];?>" class="light-blue"><i class="fa fa-shopping-cart"></i></a></li>
                    <?php endif;?>
                </ul>
                </div>
                </div>
                <div class="profile-container">
                    <div id="horizontalTab">
                        <ul class="resp-tabs-list">
                            <li>Maklumat</li>
                            <li>Aktiviti laman</li>
                            <li>Kursus</li>
                        </ul>
                        <div class="resp-tabs-container">
                            <div>
                                <p>
                                    <?php
                                    if(strip_tags($row['userProfileIntroductional']) != ""):
                                    echo nl2br($row['userProfileIntroductional']);?>
                                    <?php else:?>
                                    Pengguna ini masih tidak punyai maklumat mengenai dirinya lagi
                                    <?php endif;?>
                                </p>
                            </div>
                            <div>
                            <div class="profile-activity-forum">
                            <div class="heading-category">Forum</div>
                            <div class="profile-activity-forum-container">
                                <?php
                                if($activities_forum)
                                {
                                    echo "<ul>";
                                    foreach($activities_forum as $row_ua):?>
                                    <?php
                                    $href   = url::createByRoute("api-redirect-useractivity",Array("type"=>$row_ua['row']['userActivityType'],"userActivityID"=>$row_ua['row']['userActivityID']),true);
                                    $date   = dateRangeViewer($row_ua['row']['userActivityCreatedDate'],1,"my");
                                    ?>
                                    <li class="clearfix">
                                        <i class="fa fa-folder-open"></i>
                                        <div class="forum-activity-title">
                                        <a href="<?php echo $href;?>"><?php echo $row_ua['text'];?></a>
                                        <span><?php echo $date;?></span>
                                        </div>
                                    </li>
                                    <?php
                                    endforeach;
                                    echo "</ul>";
                                }
                                else
                                {
                                    ?>
                                    <div>
                                        Tiada aktiviti yang terkini.
                                    </div>
                                    <?php
                                }

                                ?>
                            </ul>
                            </div>
                            </div>
                            <div class="profile-activity-calendar">
                                <div class="heading-category">Kalendar Aktiviti</div>
                                <div class="profile-activity-forum-container">
                                <?php
                                if($activities_activity)
                                {
                                    echo "<ul>";
                                    foreach($activities_activity as $row_ua):
                                    $href   = url::createByRoute("api-redirect-useractivity",Array("type"=>$row_ua['row']['userActivityType'],"userActivityID"=>$row_ua['row']['userActivityID']),true);
                                    $date   = dateRangeViewer($row_ua['row']['userActivityCreatedDate'],1,"my");
                                        ?>
                                    <li class="clearfix">
                                        <i class="fa fa-calendar"></i>
                                        <div class="forum-activity-title">
                                        <a href="<?php echo $href;?>"><?php echo $row_ua['text'];?></a>
                                        <span><?php echo $date;?></span>
                                        </div>
                                    </li>
                                    <?php endforeach;
                                    echo "</ul>";
                                }
                                else
                                {
                                    ?>
                                    <div>Tiada aktiviti terkini.</div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                            <div class="heading-category">Komen Terkini</div>
                                <div class="profile-activity-forum-container">
                                <ul>
                                <?php
                                    if($activities_comment)
                                    {
                                        echo "<ul>";
                                        foreach($activities_comment as $row_ua):?>
                                        <?php
                                        $href   = url::createByRoute("api-redirect-useractivity",Array("type"=>$row_ua['row']['userActivityType'],"userActivityID"=>$row_ua['row']['userActivityID']),true);
                                        $date   = dateRangeViewer($row_ua['row']['userActivityCreatedDate'],1,"my");
                                        ?>
                                        <li class="clearfix">
                                            <i class="fa fa-folder-open"></i>
                                            <div class="forum-activity-title">
                                            <a href="<?php echo $href;?>"><?php echo $row_ua['text'];?></a>
                                            <span><?php echo $date;?></span>
                                            </div>
                                        </li>
                                        <?php
                                        endforeach;
                                        echo "</ul>";
                                    }
                                    else
                                    {
                                        ?>
                                        <div>
                                            Tiada komen terkini.
                                        </div>
                                        <?php
                                    }

                                    ?>
                                </ul>
                                </div>
                            </div>

                            <!-- Kelas-->
                            <div>
                                <!-- loop -->
                                <!-- <div class="heading-category">Pakej</div> -->
                                <div class="profile-activity-forum-container">
                                    <div class="panel-group m-b" id="accordion2">

                                    <?php
                                    //print_r($row);
                                     if ($activities_lms){
                                        $x = 1;
                                        foreach ($activities_lms as $package) {
                                            //print_r($package);
                                            //die;
                                            # code...
                                    ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="<?php echo '#collapse' . $x;?>">
                                                  <?php 
                                                  echo $package["PackageName"];
                                                    if ($package["completepayment"] == 1){
                                                        echo " (Dibayar)";
                                                    }
                                                  ?>
                                                </a> 
                                                </div>
                                                <div id="<?php echo 'collapse' . $x;?>" class="panel-collapse collapse">
                                                    <div class="panel-body text-sm"> 
                                                        <ul style='border-bottom:1px solid #e8e8e8' >
                                                            <?php
                                                                    //echo "<div><ul style='border-bottom:1px solid #e8e8e8' >";
                                                                    foreach ($package["modules"] as $module) {
                                                                        # code...
                                                                        //print_r($module);
                                                                        echo "<li class='clearfix dd-item'>
                                                                                
                                                                                ";
                                                                        echo $module["name"];
                                                                        if ($module["selected"] == 1){
                                                                            echo "<i class='fa fa-check'></i>";
                                                                            if($module["status"] != 1){
                                                                                if ($package["completepayment"] == 1){
                                                                                echo "<a href='../lms/login?moduleid=" .$module["id"]."&userid=" .$row["userID"]. "&packageid=" .$package["packageid"]."' class='bttn-submit-success' id='takequiz' style='margin-top:0px; float:right; color: white'>Ambil Kuiz</a>";
                                                                                }//if payment
                                                                                else if ($package["completepayment"] == 0){
                                                                                    echo "<a href='#' class='bttn-submit-gray' style='margin-top:0px; float:right; color: white'>Ambil Kuiz</a>";
                                                                                }                                                        
                                                                            }//if status
                                                                            else {
                                                                                echo "<a href='#' class='bttn-submit-pass' style='margin-top:0px; float:right; color: white; padding:8px 45px'>Lulus</a>";
                                                                            }
                         

                                                                        }//if
                                                                        else{//not selected
                                                                            echo "<i style='margin-left:10px'>.</i>";
                                                                            echo "<a href='#' class='bttn-submit-gray' style='margin-top:0px; float:right; color: white'>Ambil Kuiz</a>";
                                                                        }
                                                                        echo "</li>";
                                                                    }//foreach
                                                                    //echo '<br>';

                                                                    //echo "<span class='clearfix'><br></span>";

                                                                    // if ($package["complete"] == 1)
                                                                    //     echo "<a href='../lms/quizs/login?packageid=" .$package["packageid"]."&userid=" .$row["userID"]. "' class='bttn-submit-success' id='takequiz'>Ambil Kuiz</a>";
                                                                    //     //echo "<a href='#' class='bttn-submit-success' id='takequiz'>Ambil Kuiz</a>";
                                                                    // else
                                                                    //     echo "<a href='#' class='bttn-submit-gray'>Belum Lengkap</a>";

                                                                    // echo "<span class='clearfix'><br></span>";
                                                                    echo "</ul>";

                                                                    $x++;
                                                                
                                                             
                                                            ?>

                                                        </ul>
                                                    </div><!--panelbody-->
                                                </div><!--collapseid-->  
                                            </div><!-- paneldefault-->
                                            <?php 
                                                    }//foreach package
                                             }//if activities_lms
                                             else{
                                                echo "<h3> Sila hubungi pi1m anda untuk pendaftaran kursus </h3>";
                                             }
                                            ?>
                                </div>
                                </div>
                                <!-- end loop -->                              
                            </div>
                            <!-- Kelas -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var $t = jQuery.noConflict();
    $t(document).ready(function () {
        $t('#horizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion           
            width: 'auto', //auto or any width like 600px
            fit: true,   // 100% fit in a container
            closed: 'accordion', // Start closed if in accordion view
            activate: function(event) { // Callback function if tab is switched
                var $tab = $t(this);
                var $info = $t('#tabInfo');
                var $name = $t('span', $info);

                $name.text($tab.text());

                $info.show();
                console.log($info);
                //if ($tab == )
            }
        });
    });
</script>