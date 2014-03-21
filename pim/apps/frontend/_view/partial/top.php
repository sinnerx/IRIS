<!-- Top Header start -->
<div class="top-header">
	<div class="wrap clearfix">
		<div class="other-location">
			<div class="wrapper-dropdown-2" tabindex="1">Ke PI1M Lain
				<ul class="dropdown">
					<?php controller::load("partial","pim_list");?>
				</ul>
			</div>
		</div>
	<div class="social-network clearfix">
		<ul>
			<li><i class="fa fa-facebook"></i></li>
			<li><i class="fa fa-twitter"></i></li>
			<li><i class="fa fa-envelope"></i></li>
		</ul>
	</div>
	<div class="user-setting">
	<form>
		<input type="text" class="username" placeholder='Username'>
		<input type="password" class="password" placeholder='Password'> 
		<input type="submit" class="submit" value="Login">
		<a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>" class="rgstr-button">Register</a>
	</form>
	</div>
	</div>
</div> <!-- Top Header End -->