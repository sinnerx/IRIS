<h3 class="block-heading"><a href='.'>PROFIL AHLI</a> <span class="subforum"> > Pemegang kad</span> </h3>
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
                <?php /*
                [USER ACTIVITY]
                <div class="profile-user-activity" style="opacity:0.2;">
                <h3>Aktiviti</h3>
                    <div class="profile-activity-list">
                        <ul>
                            <li>
                                <a href="#">Hashim Meninggalkan Komen di Forum “ Siapa Boleh Bekalkan atap nipah? ”</a>
                                <span> 2 Minit Lalu </span>
                                </li>
                                <li>
                                <a href="#">Razak mengemaskini profil peribadinya.</a>
                                <span> 12 Minit Lalu </span>
                                </li>
                                <li>
                                <a href="#">Kamal Meninggalkan Komen di Galeri Foto “Pembukaan Ruangan Baru”.</a>
                                <span> 2 Jam Lalu </span>
                                </li>
                                <li>
                                <a href="#">Rahman akan hadir ke “ Latihan Asas Etika Email ”.</a>
                                <span> 15 Jam Lalu </span>
                            </li>
                        </ul>
                    </div>
                </div>*/?>
            </div>
            <div class="rght-sidebar">
                <div class="profile-user-info">
                <h3><?php echo $row['userProfileFullName'];?></h3>
                <span class="profile-user-occupation"><?php echo $row['userProfileOccupation']?:"Menganggur";?></span>
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
                                <!-- <p>Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.<br>
                                <br>
                                Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.<br>
                                <br>
                                Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.</p> -->
                            </div>
                            <?php /*
                            [USER ACTIVITY]
                            <div style="opacity:0.1;cursor:default;">
                            <div class="profile-activity-forum">
                            <div class="heading-category">Forum</div>
                            <div class="profile-activity-forum-container">
                            <ul>
                                <li class="clearfix">
                                <i class="fa fa-folder-open"></i>
                                <div class="forum-activity-title">
                                <a href="#">Hashim meninggalkan komen di Forum "Siapa boleh bekalkan atap nipah?"</a>
                                <span>2 Jam Yang Lalu</span>
                                </div>
                                </li>
                                <li class="clearfix">
                                <i class="fa fa-folder-open"></i>
                                <div class="forum-activity-title">
                                <a href="#">Hashim meninggalkan komen di Forum "Siapa boleh bekalkan atap nipah?"</a>
                                <span>2 Jam Yang Lalu</span>
                                </div>
                                </li>
                                <li class="clearfix">
                                <i class="fa fa-folder-open"></i>
                                <div class="forum-activity-title">
                                <a href="#">Hashim meninggalkan komen di Forum "Siapa boleh bekalkan atap nipah?"</a>
                                <span>2 Jam Yang Lalu</span>
                                </div>
                                </li>
                            </ul>
                            </div>
                            </div>
                            <div class="profile-activity-calendar">
                            <div class="heading-category">Kalendar Aktiviti</div>
                            <div class="profile-activity-forum-container">
                            <ul>
                            <li class="clearfix">
                            <i class="fa fa-calendar"></i>
                            <div class="forum-activity-title">
                            <a href="#">Hashim menghadirkan diri ke "Latihan Asas Etika Emel"</a>
                            <span>Minggu Lepas</span>
                            </div>
                            </li>
                            </ul>
                            </div>
                            </div>
                            <div class="profile-activity-file">
                            <div class="heading-category">Muat Naik Fail</div>
                            <div class="profile-activity-forum-container">
                            <ul>
                            <li class="clearfix">
                            <div class="icon-pdf"></div>	
                            <div class="forum-activity-title">
                            <a href="#">Brosur Sos Cili Kayu Seribu</a>
                            <span>3 Hari Lepas</span>
                            </div>
                            </li>
                            </ul>
                            </div>
                            </div>
                            <div class="profile-activity-comment">
                            <div class="heading-category">Komen Terkini</div>
                            <div class="profile-activity-forum-container">
                            <ul>
                            <li class="clearfix">
                            <i class="fa fa-comments"></i> 
                            <div class="forum-activity-title">
                            <a href="#">Hashim meninggalkan komen di Galeri Foto "Pembukaan Ruangan Baru"
                            </a>
                            <span>Minggu Lepas</span>
                            </div>
                            </li>
                            <li class="clearfix">
                            <i class="fa fa-comments"></i> 

                            <div class="forum-activity-title">
                            <a href="#">Hashim meninggalkan komen di Galeri Foto "Pembukaan Ruangan Baru"
                            </a>
                            <span>Minggu Lepas</span>
                            </div>
                            </li>

                            <li class="clearfix">
                            <i class="fa fa-comments"></i> 

                            <div class="forum-activity-title">
                            <a href="#">Hashim meninggalkan komen di Galeri Foto "Pembukaan Ruangan Baru"
                            </a>
                            <span>Minggu Lepas</span>
                            </div>
                            </li>
                            </ul>
                            </div>
                            </div>
                            </div> */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>