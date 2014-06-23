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

.in-active
{
	position: absolute;
	background: #8c2f2f;
	color:white;
	right:0px;
	box-shadow: 0px -5px 5px #000000;
	padding:3px 6px 3px 6px;
	line-height:normal;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
	width:220px;
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
						<?php # controller::load("partial","pim_list");?>
					</div>
				</ul>			
			</div>
		</div>
	<div class="social-network clearfix">
		<ul>
			<?php 
			if($links['siteInfoFacebookUrl']):?>
			<li><a target='_blank' href='//<?php echo str_replace(Array("http://","https://"), "", $links['siteInfoFacebookUrl']);?>' class="fa fa-facebook"></a></li>
			<?php endif;
			if($links['siteInfoTwitterUrl']):?>
			<li><a target='_blank' href='//<?php echo str_replace(Array("http://","https://"), "", $links['siteInfoTwitterUrl']);?>' class="fa fa-twitter"></a></li>
			<?php endif;
			if($links['siteInfoEmail']):?>
			<li><a href='mailto:<?php echo $links['siteInfoEmail'];?>' class="fa fa-envelope"></a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="user-setting">
	<?php 
	## logged in
	if($username):?>
		<span style='vertical-align:top;color:#009BFF;position:relative;left:-5px;font-weight:lighter;'>
		<span style='color:#888888;'>Selamat datang,</span> <a href='<?php echo url::base("{site-slug}/profile");?>' style='color:inherit;'><?php echo $username;?></a></span>
		<a href='<?php echo url::base("{site-slug}/logout");?>' class='fa fa-power-off' style='color:#eb1414;position:relative;top:1px;'></a>

		<?php if(!authData("user.isActive")):?>
			<div class='in-active'>
			Akaun anda masih belum aktif.
			</div>
		<?php endif;?>
	<?php
	## not logged.
	else:?>
	<form method='post' action='<?php echo url::base("{site-slug}/login");?>'>
		<input type="text" name='login_userIC' class="username" placeholder='Kad Pengenalan'>
		<input type="password" name='login_userPassword' class="password" placeholder='Kata Laluan'> 
		<input type="submit" class="submit" value="Login">
		<a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>" class="rgstr-button">Register</a>
	</form>
<?php endif;?>
	</div>
	</div>	
</div> <!-- Top Header End -->