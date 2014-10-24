<script src="<?php echo url::asset("_templates/js/easyResponsiveTabs.js");?>" type="text/javascript"></script>
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
                                foreach($activities as $row_ua):
                                $date   = dateRangeViewer($row_ua['row']['userActivityCreatedDate'],1,"my");
                                $row    = $row_ua['row'];
                                $href   = url::createByRoute("api-redirect-useractivity",Array("type"=>$row['userActivityType'],"userActivityID"=>$row['userActivityID']),true);
                                    ?>
                                <li>
                                    <a href='<?php echo $href;?>'><?php echo $row_ua['text'];?></a>
                                    <span><?php echo $date;?></span>                                    
                                </li>
                                <?php
                                endforeach;
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="rght-sidebar">
                <div class="profile-user-info">
                <h3><?php echo $row['userProfileFullName'];?>
                </h3>
                <span class="profile-user-occupation"><?php echo $row['userProfileOccupation']?:"-";?></span>
                <span class="profile-user-location" style='position:relative;top:8px;'><span><?php echo $siteName;?></span><span><a href="#"><i class="fa fa-envelope"></i></a></span></span>
                <div class="profile-social-media">
                <ul>
                    <?php 
                    if($row['userProfileFacebook']):?>
                        <li><a target="_blank" href="<?php echo $row['userProfileFacebook'];?>"><i class="fa fa-facebook"></i></a></li>
                    <?php endif;
                    if($row['userProfileTwitter']):?>
                        <li><a target="_blank" href="<?php echo $row['userProfileTwitter'];?>" class="light-blue"><i class="fa fa-twitter"></i></a></li>
                    <?php endif;
                    if($row['userProfileWeb']):?>
                        <li><a target="_blank" href="<?php echo $row['userProfileWeb'];?>"><i class="fa fa-link"></i></a></li>
                    <?php endif;
                    if($row['userProfileEcommerce']):?>
                        <li><a target="_blank" href="<?php echo $row['userProfileEcommerce'];?>" class="light-blue"><i class="fa fa-shopping-cart"></i></a></li>
                    <?php endif;?>
                </ul>
                </div>
                </div>
                <div class="profile-container">
                    <div id="horizontalTab">
                        <ul class="resp-tabs-list">
                            <li>Maklumat</li>
                            <li>Aktiviti laman</li>
                        </ul>
                        <div class="resp-tabs-container">
                            <div>
                                <p>
                                    <?php
                                    if($row['userProfileIntroductional'] != ""):
                                    echo nl2br($row['userProfileIntroductional']);?>
                                    <?php else:?>
                                    - Pengguna ini masih tidak punyai maklumat mengenai dirinya lagi -
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
            }
        });
    });
</script>