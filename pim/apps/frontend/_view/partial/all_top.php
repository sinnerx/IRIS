<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- Responsive Code -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:700);
	</style>
	<link href="<?php echo url::asset("frontend/css/styles.css");?>" rel="stylesheet" type="text/css">
	<!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> -->
	<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/font-awesome.min.css");?>">
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
	<script type="text/javascript" src='<?php echo url::asset("_scale/js/jquery.min.js");?>'></script>
	<script src="<?php echo url::asset("frontend/js/jquery.ticker.js");?>" type="text/javascript"></script>
	<link href="<?php echo url::asset("_landing/css/jquery.mCustomScrollbar.css");?>" rel="stylesheet" type="text/css" /> <!-- used by partial/top -->
	<script src="<?php echo url::asset("_landing/js/jquery.mCustomScrollbar.concat.min.js");?>"></script> <!-- used by partial/top -->
	<script src="<?php echo url::asset("frontend/js/site.js");?>" type="text/javascript"></script>

	<!-- Responsive Code -->
	<style type="text/css">

	.area-mobile, .mobile-top, .logo-mobile
	{
		display: none;
	}

	</style>
	<link href="<?php echo url::asset("frontend/responsive/css/responsive.css");?>" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/slidebars.css");?>">
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/example-styles.css");?>">
	<!------>
</head>
<body>
<div class="mobile-top">
	<div class="mobile-icon-left"></div>
	<div class="mobile-site-name">Felda Bukit Tangga</div>
	<div class="mobile-navigation-button">
	<div class="sb-toggle-right"><i class="fa fa-bars"></i></div>
	</div>
</div>
<div class="area-mobile">
	<div class="mobile-cp">
		<ul>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>">Login</a></li>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>">Register</a></li>
		</ul>
	</div>
	<div class="area-mobile-wrap">
		<select>
			<option selected="selected">Ke Calent Lain</option>
			<option disabled>Johor</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option disabled>Kedah</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option disabled>Negeri Sembilan</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option disabled>Sabah</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
		</select>
	</div>
</div>
<?php controller::load("partial","pim_list");?>
<div class="main-wrap">
<style type="text/css">
	
.social-network .fa
{
	color:inherit;
}

.social-network .fa.fa-facebook:hover
{
	color: #728cc2;
}

.social-network .fa.fa-twitter:hover
{
	color: #55acee;
}

.social-network .fa.fa-envelope:hover
{
	color: white;
}

.top-message
{
	box-shadow: 0px 3px 5px #820000;
	padding:3px 6px 3px 6px;
	line-height:normal;
	width:100%;
	text-align:center;
}

.top-message.in-active
{
	background: #8c2f2f;
	background: #d90000;
	color:white;
}
.user-setting
{
	position: relative;
}

</style>
<!-- Top Header start -->
<div class="top-header">
	<div class="wrap clearfix">
		<div class="other-location">
			<div class="wrapper-dropdown-2" tabindex="1" onclick='dropdownfix.removeFocus();'>Ke PI1M Lain
				<ul class="dropdown">
					<div id="content_7" class="content">
											</div>
				</ul>			
			</div>
		</div>
	<div class="social-network clearfix">
		<ul>
					</ul>
	</div>
	<div class="user-setting">
		<form method='post' action='http://localhost/irix/calent/login'>
		<input type="text" name='login_userIC' class="username" placeholder='Kad Pengenalan'>
		<input type="password" name='login_userPassword' class="password" placeholder='Kata Laluan'> 
		<input type="submit" class="submit" value="Log Masuk">
		<a href="http://localhost/irix/calent/registration#horizontalTab2" class="rgstr-button">Daftar</a>
	</form>
	</div>
	</div>	
</div> <!-- Top Header End -->
<div class="main-container">
	<div class="wrap">
		<div class="body-container front-info clearfix">
			<script type="text/javascript">
     var $t = jQuery.noConflict();
    $t(document).ready(function () {
        $t('#act').easyResponsiveTabs({
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
    min-height:900px;
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
     calent     </div>
     <div class="front-content">
     
	          
	          
	          
	          
	          
	          
	          
	          	        	        	        	        	        	        	        	             </div>
     <div class="front-guide">
     Hadapi Masalah? <a href="#"> Rujuk Panduan </a>
     </div>
     <div class="back-home">
     <a href="http://localhost/irix/calent"><i class="fa fa-angle-double-left"></i>Kembali ke muka hadapan</a>
     </div>
</div>

<div class="login-register-block">
     <div id="act">
          <ul class="resp-tabs-list front-tab">
          <li>Log Masuk</li>
          <li>Daftar Akaun Baru</li>
          </ul>
          <br><br>          <div class="resp-tabs-container">
               <div>
                    <div class="login">
                    <form method='post' action='http://localhost/irix/calent/login'>
                         <label>KAD PENGENALAN </label>
                              <input name='login_userIC' id='login_userIC' type='text'  class='name username-login' placeholder='Kad Pengenalan Anda' value='' />                         <label style="margin-top:15px;">KATA LALUAN </label>
                              <input type='password' name='login_userPassword'  id='login_userPassword' class='name password-login' placeholder='Kata Laluan Anda' value='' />                            <!-- <div class="help-login"><a href="#">Lupa Kata Laluan? </a></div> -->
                    <div class="bottom-button clearfix">
                    <input type="submit" class="submit-bttn" value="Log Masuk"> <!-- <a href="#" class="fb-login"><i class="fa fa-facebook"></i>  Log Masuk Guna Facebook</a> -->
                    </div>
                    </form>
                    </div>
               </div>
               <div>
               <div class="register">
               <form method='post'>
                 <label>NAMA </label>
                 <input name='userProfileFullName' id='userProfileFullName' type='text'  style='width:40%;display:inline;' class='name username-login' placeholder='Nama' value='' />                 <input name='userProfileLastName' id='userProfileLastName' type='text'  style='width:45%;display:inline;' class='name username-login' placeholder='Nama Ayah' value='' />                 <label>KAD PENGENALAN </label>
                 <input name='userIC' id='userIC' type='text'  class='name username-login' placeholder='Kad Pengenalan Anda' value='' />                 
                 <label>KATA LALUAN </label>
                 <input type='password' name='userPassword'  id='userPassword' class='name password-login' placeholder='Kata Laluan Anda' value='' />                 <label>ULANG KATA LALUAN </label>
                 <input type='password' name='userPasswordConfirm'  id='userPasswordConfirm' class='name password-login' placeholder='Kata Laluan Anda' value='' />                 <label>Tarikh Lahir </label>
                 <div class="dob clearfix">
                 <div class="dob-month">
                 <select name='birthday_month'  id='birthday_month' ><option value=''>Bulan</option><option value='1' >JANUARI</option><option value='2' >FEBRUARI</option><option value='3' >MAC</option><option value='4' >APRIL</option><option value='5' >MEI</option><option value='6' >JUN</option><option value='7' >JULAI</option><option value='8' >OGOS</option><option value='9' >SEPTEMBER</option><option value='10' >OKTOBER</option><option value='11' >NOVEMBER</option><option value='12' >DISEMBER</option></select>                 </div>
                 <div class="dob-day">
                 <!-- <select id="day" name="birthday_day"><option selected="1" value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> -->
                 <select name='birthday_day'  id='birthday_day' ><option value=''>Hari</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='7' >7</option><option value='8' >8</option><option value='9' >9</option><option value='10' >10</option><option value='11' >11</option><option value='12' >12</option><option value='13' >13</option><option value='14' >14</option><option value='15' >15</option><option value='16' >16</option><option value='17' >17</option><option value='18' >18</option><option value='19' >19</option><option value='20' >20</option><option value='21' >21</option><option value='22' >22</option><option value='23' >23</option><option value='24' >24</option><option value='25' >25</option><option value='26' >26</option><option value='27' >27</option><option value='28' >28</option><option value='29' >29</option><option value='30' >30</option><option value='31' >31</option></select>                 </div>
                 <div class="dob-year">
                 <select name='birthday_year'  id='birthday_year' ><option value=''>Tahun</option><option value='1925' >1925</option><option value='1926' >1926</option><option value='1927' >1927</option><option value='1928' >1928</option><option value='1929' >1929</option><option value='1930' >1930</option><option value='1931' >1931</option><option value='1932' >1932</option><option value='1933' >1933</option><option value='1934' >1934</option><option value='1935' >1935</option><option value='1936' >1936</option><option value='1937' >1937</option><option value='1938' >1938</option><option value='1939' >1939</option><option value='1940' >1940</option><option value='1941' >1941</option><option value='1942' >1942</option><option value='1943' >1943</option><option value='1944' >1944</option><option value='1945' >1945</option><option value='1946' >1946</option><option value='1947' >1947</option><option value='1948' >1948</option><option value='1949' >1949</option><option value='1950' >1950</option><option value='1951' >1951</option><option value='1952' >1952</option><option value='1953' >1953</option><option value='1954' >1954</option><option value='1955' >1955</option><option value='1956' >1956</option><option value='1957' >1957</option><option value='1958' >1958</option><option value='1959' >1959</option><option value='1960' >1960</option><option value='1961' >1961</option><option value='1962' >1962</option><option value='1963' >1963</option><option value='1964' >1964</option><option value='1965' >1965</option><option value='1966' >1966</option><option value='1967' >1967</option><option value='1968' >1968</option><option value='1969' >1969</option><option value='1970' >1970</option><option value='1971' >1971</option><option value='1972' >1972</option><option value='1973' >1973</option><option value='1974' >1974</option><option value='1975' >1975</option><option value='1976' >1976</option><option value='1977' >1977</option><option value='1978' >1978</option><option value='1979' >1979</option><option value='1980' >1980</option><option value='1981' >1981</option><option value='1982' >1982</option><option value='1983' >1983</option><option value='1984' >1984</option><option value='1985' >1985</option><option value='1986' >1986</option><option value='1987' >1987</option><option value='1988' >1988</option><option value='1989' >1989</option><option value='1990' >1990</option><option value='1991' >1991</option><option value='1992' >1992</option><option value='1993' >1993</option><option value='1994' >1994</option><option value='1995' >1995</option><option value='1996' >1996</option><option value='1997' >1997</option><option value='1998' >1998</option><option value='1999' >1999</option><option value='2000' >2000</option><option value='2001' >2001</option><option value='2002' >2002</option><option value='2003' >2003</option><option value='2004' >2004</option><option value='2005' >2005</option><option value='2006' >2006</option><option value='2007' >2007</option><option value='2008' >2008</option><option value='2009' >2009</option><option value='2010' >2010</option><option value='2011' >2011</option><option value='2012' >2012</option><option value='2013' >2013</option><option value='2014' >2014</option><option value='2015' >2015</option></select>                 </div>
                 </div>

                 <!-- GENDER -->
                 <div style="height:50px;margin-top:10px;">
                 <div style="width:40%;float:left;">
                     <label>Jantina </label>
                     <div class='dob clearfix'>
                        <div class='dob-year'>
                            <select name='userProfileGender'  id='userProfileGender' ><option value=''>Jantina</option><option value='1' >Lelaki</option><option value='2' >Perempuan</option></select>                        </div>
                     </div>
                </div>
                <div style="width:40%;float:left;">
                     <!-- OCCUPATION -->
                     <label>Pekerjaan </label>
                     <div class='dob clearfix'>
                        <div class='dob-year' style="width: 180px;">
                                                        <select name='userProfileOccupationGroup'  id='userProfileOccupationGroup' style='width:200px;'><option value=''>Pekerjaan</option><option value='1' >Pelajar</option><option value='2' >Suri-rumah</option><option value='3' >Kerja sendiri</option><option value='4' >Di bawah majikan</option><option value='5' >Tidak bekerja</option><option value='6' >Bersara</option><option value='7' >Bukan Pelajar</option></select>                        </div>
                     </div>
                 </div>
                 </div>
                 <div class="check-agree">
                 <div class="row-check clearfix">


                 <label>
                 <div class="squaredFour">
                                  <input type="checkbox" value="selected" id="squaredFive" name="checkPenduduk"  />
                 <label for="squaredFive"></label>
                 </div>
                                  <span>Saya penduduk sekitar calent</span>
                 </label>
                 </div>
                 <div class="row-check clearfix">
                 <label>
                 <div class="squaredFour">
                 <input type="checkbox" value="selected" selected id="squaredFour" name="checkTerm" />
                 <label for="squaredFour"></label>
                 </div>
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
</div>		</div>
	</div>
</div>
<div class="footer">
	<div class="wrap clearfix">
		<div class="copyright">
		Hakcipta Terpelihara © 2016 <a href="#">Calent</a>
		<ul class="clearfix">
			<li><a href="http://localhost/irix/calent">Utama</a></li>
			<li><a href="http://localhost/irix/calent/mengenai-kami">Mengenai Kami</a></li>
			<li><a href="http://localhost/irix/calent/hubungi-kami">Hubungi Kami</a></li>
		</ul>
		</div>
	<div class="logo-bottom">
		<ul class="clearfix">
			<!-- <li><a target="_blank" href='http://www.skmm.gov.my'><img src="http://localhost/irix/pim/assets/frontend/images/mcmc_logo.png" width="72" height="46"  alt=""/></a></li> -->
			<li><a target="_blank" href='http://www.celcom.com.my'><img src="http://localhost/irix/pim/assets/frontend/images/vMCMC/celcom_bottom.png" width="87" height="46"  alt=""/></a></li>
			<!-- <li><a href='http://localhost/irix'><img src="http://localhost/irix/pim/assets/frontend/images/pi1m_bottom.png" width="241" height="46"  alt=""/></a></li> -->
		</ul>
	</div>
	</div>
</div>
</div><!-- main-wrap -->

<!-- Responsive Code -->
<div class="sb-slidebar sb-right sb-style-overlay">
<div class="mobile-navigation-header clearfix"><div class="sb-close menu-close"><i class="fa fa-times"></i></div><div class="menu-name">Menu</div></div>
	<div class="mobile-navigation-content">
		<ul>
		    <li><a href="#">Utama</a></li>
		    <li><a href="#">Mengenai Kami</a></li>
		    <li><a href="#">Aktiviti</a></li>
		    <li>Ruangan Ahli</li>
		   	 <ul>
		 		<div>
				<li class="submenu-heading">Kalendar Aktiviti</li>
		        <li><a href="#">Aktiviti Akan Datang</a></li>
		        <li> <a href="#">Aktiviti Lepas</a></li>
				</div>
		        <div>
				<li class="submenu-heading">Galeri Media</li>
		        <li> <a href="#">Galeri Foto</a></li>
					<li><a href="#">Galeri Video</a></li>
		            <li><a href="#">Galeri Muat Turun</a></li>
				</div>
			</ul>
	    </ul>
    </div>
</div>
<!-- Slidebars for responsive top menu -->
<script src="http://localhost/irix/pim/assets/frontend/responsive/js/slidebars.js"></script>
<script>
	(function($) {
		$(document).ready(function() {
			$.slidebars();
		});
	}) (jQuery);
</script>
<!------>
	<!-- <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script> -->
	    <script type="text/javascript" src="http://localhost/irix/pim/assets/frontend/js/jquery.nivo.slider.js"></script>
	    <script type="text/javascript">
		var $s = jQuery.noConflict();
	    $s(window).load(function() {
	    $s('#slider').nivoSlider();
	    });
	    </script>
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
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
	</script>
</body>
</html>