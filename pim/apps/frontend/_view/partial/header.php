<div class="header">
	<div class="wrap">
		<div class="logo">
			<h1><a href='<?php echo url::base("{site-slug}");?>' style='color:inherit;'><?php echo $siteName;?></a></h1>
		</div>

		<div class='navigation'>
		<ul class='nav'>
		<?php
		if($menuR)
		{
			## prepare component controller mapping first.
			$componentControllerR	= Array(
									1=>"page/index",
									2=>"main/index",
									3=>"activity/index",
									4=>"member/index",
									5=>"main/contact"
											);

			foreach($menuR as $row)
			{
				## if component status deactivated, skip.
				if($row['componentStatus'] == 0)
				{
					continue;
				}

				

				$component	= $row['componentNo'];
				$menuName	= !$row['componentName']?$row['menuName']:$row['componentName'];
				$main_url	= url::base(request::named("site-slug")."/".$row['componentRoute']);
				$pageID		= null;

				## skip certain component for not user.
				if(in_array($component,Array(4)) && !session::has("userID"))
				{
					continue;
				}

				## pages
				if($component == 1)
				{
					$page	= model::load("page/page");

					## get default.. (like name or slug, can be used if tyoe = 1[default])
					$defaultR	= $page->getDefault();
					$pageID	= $row['menuRefID'];

					## get page.
					$row_page	= $page->getPage($pageID);

					$defaultType	= $row_page['pageDefaultType'];

					## if type 1, use default slug.
					$main_url		= $row_page['pageType'] == 1?url::base(request::named('site-slug')."/".$defaultR[$defaultType]['pageDefaultSlug']):$main_url;
					$menuName		= $row_page['pageType'] == 1?$defaultR[$defaultType]['pageDefaultName']:$row_page['pageName'];

					## check children page.
					$childPage	= $page->getChildrenPage($pageID);

					if($childPage)
					{
						foreach($childPage as $row)
						{
							$childDefaultType	= $row['pageDefaultType'];

							## get child page url.

							## default check on $defaultR.
							$childPageURL	= $main_url."/".($row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultSlug']:$row['pageSlug']);
							$pageName		= $row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultName']:$row['pageName'];

							## childpage R
							$childPageR[$pageID][]	= Array($childPageURL,$pageName);
						}
					}
				}
				
				## end page component.

				## echo the menu.
				$controller	= controller::getCurrentController();
				$method		= controller::getCurrentMethod();

				$cssActive	= $controller."/".$method == $componentControllerR[$component]?"active":"";

				$dropdownIcon	= isset($childPageR[$pageID])?'<span><i class="fa fa-sort-asc"></i></span>':"";
				echo "<li><a href='$main_url' class='$cssActive'>$menuName $dropdownIcon</a>";

				## for first appearance in childmenu. github #10
				$firstmenu	= "<li><a href='".$main_url."'>$menuName</a></li>";
				if($component == 1)
				{
					if(isset($childPageR[$pageID]))
					{
						echo "<div>";
						echo '<div class="nav-column"><div class="menu-block">';
						echo "<ul class='clearfix'>";
						echo "<h3>$menuName</h3>";
						$no	= 1;
						foreach($childPageR[$pageID] as $row)
						{
							if($no == 1)
							{
								echo $firstmenu;
							}
							echo "<li><a href='".$row[0]."'>$row[1]</a></li>";
							$no++;
						}

						echo "</ul>";
						echo "</div></div>";
						echo "</div>";
					}
				}


				echo "</li>";
			}
		}

		?>
		</ul>
		</div>

		<?php /*
		<div class="navigation" style="display:none;">
			<ul class="nav">
				<li><a href="#" class="active">Utama</a></li>
				<li><a href="<?php echo url::base("{site-slug}/about-us");?>">Mengenai Kami</a>
					<div>
						<div class="nav-column">
			            	<div class="menu-block">
							<ul class="clearfix">
								<h3>Mengenai Kami</h3>
								<li><a href="#">Pengurus Laman</a></li>
								<li><a href="#">Maklumat Kampung</a></li>
							</ul>
			                </div>
			            </div>
			        </div>
				</li>
				<li><a href="#">Aktiviti</a></li>
				<li><a href="#">Ruangan Ahli</a>
			        <div>
						<div class="nav-column">
			            	<div class="menu-block">
							<h3>Kalendar Aktiviti</h3>
							<ul class="clearfix">
								<li><a href="#">Aktiviti Akan Datang</a></li>
								<li><a href="#">Aktiviti Lepas</a></li>
							</ul>
			                </div>
			                <div class="menu-block">
			                <h3>Galeri Media</h3>
							<ul class="clearfix">
								<li><a href="#">Galeri Foto</a></li>
								<li><a href="#">Galeri Video</a></li>
			                  <li><a href="#">Galeri Muat Turun</a></li>
							</ul>
			                </div>
						</div>
			            <div class="nav-column">
			            <div class="menu-block">
							<h3>Forum</h3>
							<ul class="clearfix">
								<li><a href="#">Terkini</a></li>
								<li><a href="#">Kategori</a></li>
			                  <li><a href="#">Topik Baru</a></li>
							</ul>
			                </div>
			                <div class="menu-block">
			                <h3>Ruang Ahli</h3>
							<ul class="clearfix">
								<li><a href="#">Direktori Ahli</a></li>
								<li><a href="#">Profile Anda</a></li>
							</ul>
			                </div>
						</div>
					</div>
			    </li>
				<li><a href="#">Hubungi Kami</a></li>
			</ul>
		</div> */?>
	</div>
</div>