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
<style type="text/css">
/*temporary*/
input
{
    color:#727272 !important;
}   

</style>
<style type="text/css">
    
.span-error
{
    color:red !important;
}

.alert.alert-danger
{
    color: red;
}
.alert.alert-success
{
    color:green;
}
.main-container
{
    min-height:850px;
}

ul.front-tab li
{
    color:#009bff !important;
}

ul.front-tab li.resp-tab-active
{
    font-weight: bold !important;
    color:#555555 !important;
    text-shadow: 0px 0px 1px #585858;
}

ul.front-tab li:hover
{
    text-shadow: 0px 0px 20px #888888;
}

/* fix some ui bugs, when a lot of texts was dumpted there.*/
.left-info
{
    padding-bottom: 100px;
}

</style>
<div class="left-info">
     <div class="front-logo">
     <?php echo $siteName;?>
     </div>
     <div class="front-content">
     <?php echo $siteInfoDescription;?>
     </div>
     <div class="front-guide">
     Hadapi Masalah? <a href="#"> Rujuk Panduan </a>
     </div>
     <div class="back-home">
     <a href="<?php echo url::base("{site-slug}");?>"><i class="fa fa-angle-double-left"></i>Kembali ke muka hadapan</a>
     </div>
</div>

<div class="login-register-block">
     <div id="horizontalTab">
          <ul class="resp-tabs-list front-tab">
          <li>Log Masuk</li>
          <li>Daftar Akaun Baru</li>
          </ul>
          <br><br><?php echo flash::data();?>
          <div class="resp-tabs-container">
               <div>
                    <div class="login">
                    <form method='post' action='<?php echo url::base("{site-slug}/login");?>'>
                         <label>KAD PENGENALAN <?php echo flash::data("login_userIC");?></label>
                              <?php echo form::text("login_userIC","class='name username-login' placeholder='Kad Pengenalan Anda'");?>
                         <label style="margin-top:15px;">KATA LALUAN <?php echo flash::data("login_userPassword");?></label>
                              <?php echo form::password("login_userPassword","class='name password-login' placeholder='Kata Laluan Anda'");?>
                            <!-- <div class="help-login"><a href="#">Lupa Kata Laluan? </a></div> -->
                    <div class="bottom-button clearfix">
                    <input type="submit" class="submit-bttn" value="Log Masuk"> <!-- <a href="#" class="fb-login"><i class="fa fa-facebook"></i>  Log Masuk Guna Facebook</a> -->
                    </div>
                    </form>
                    </div>
               </div>
               <div>
               <div class="register">
               <form method='post'>
                 <label>NAMA <?php echo flash::data("userProfileFullName",flash::data("userProfileLastName"));?></label>
                 <?php echo form::text("userProfileFullName","style='width:40%;display:inline;' class='name username-login' placeholder='Nama'");?>
                 <?php echo form::text("userProfileLastName","style='width:45%;display:inline;' class='name username-login' placeholder='Nama Ayah'");?>
                 <label>KAD PENGENALAN <?php echo flash::data("userIC");?></label>
                 <?php echo form::text("userIC","class='name username-login' placeholder='Kad Pengenalan Anda'");?>
                 
                 <label>KATA LALUAN <?php echo flash::data("userPassword");?></label>
                 <?php echo form::password("userPassword","class='name password-login' placeholder='Kata Laluan Anda'");?>
                 
                 <label>Tarikh Lahir <?php echo flash::data("birthday_month",flash::data("birthday_day"),flash::data("birthday_year"));?></label>
                 <div class="dob clearfix">
                 <div class="dob-month">
                 <?php echo form::select("birthday_month",$monthR,"","","Bulan");?>
                 </div>
                 <div class="dob-day">
                 <!-- <select id="day" name="birthday_day"><option selected="1" value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> -->
                 <?php
                 ## build dayR.
                 $dayR   = Array();
                 foreach(range(1,31) as $d)
                 {
                    $dayR[$d]   = $d;
                 }

                 echo form::select("birthday_day",$dayR,"","","Hari");

                 ?>
                 </div>
                 <div class="dob-year">
                 <?php echo form::select("birthday_year",$yearR,"","","Tahun");?>
                 </div>
                 </div>

                 <!-- GENDER -->
                 <div style="height:50px;margin-top:10px;">
                 <div style="width:40%;float:left;">
                     <label>Jantina <?php echo flash::data("userProfileGender");?></label>
                     <div class='dob clearfix'>
                        <div class='dob-year'>
                            <?php echo form::select("userProfileGender",Array(1=>"Lelaki",2=>"Perempuan"),"","","Jantina");?>
                        </div>
                     </div>
                </div>
                <div style="width:40%;float:left;">
                     <!-- OCCUPATION -->
                     <label>Pekerjaan <?php echo flash::data("userProfileOccupationGroup");?></label>
                     <div class='dob clearfix'>
                        <div class='dob-year' style="width: 180px;">
                            <?php
                            $occupationR = model::load("helper")->occupationGroup();?>
                            <?php echo form::select("userProfileOccupationGroup",$occupationR,"style='width:200px;'","","Pekerjaan");?>
                        </div>
                     </div>
                 </div>
                 </div>
                 <div class="check-agree">
                 <div class="row-check clearfix">


                 <label>
                 <div class="squaredFour">
                 <?php
                    $checkPenduduk  = flash::data("_post.checkPenduduk")?"checked":"";
                 ?>
                 <input type="checkbox" value="selected" id="squaredFive" name="checkPenduduk" <?php echo $checkPenduduk;?> />
                 <label for="squaredFive"></label>
                 </div>
                 <?php echo flash::data("checkPenduduk");?>
                 <span>Saya penduduk sekitar <?php echo $siteName;?></span>
                 </label>
                 </div>
                 <div class="row-check clearfix">
                 <label>
                 <div class="squaredFour">
                 <input type="checkbox" value="selected" selected id="squaredFour" name="checkTerm" />
                 <label for="squaredFour"></label>
                 </div>
                 <?php echo flash::data("checkTerm");?>
                 <span>Saya setuju dengan <a href="#"> Terma & Syarat </a></span>
                 </label>
                 </div>
                 </div>
                 <div class="bottom-button clearfix">
                 <input type="submit" class="submit-bttn" value="Daftar"> <!-- <a href="#" class="fb-login"><i class="fa fa-facebook"></i>  Log Masuk Guna Facebook</a> -->
                 </div>
               </form>
               </div>
               </div>
          </div>
     </div>
</div>