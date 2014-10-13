<style type="text/css">
	
.search-box
{
	position: absolute;
	right:0px;
	top:-30px;
}

.search-box input
{
	padding:5px;
	border:1px solid #e8e8e8;
	border:0px;
	border-bottom:1px solid black;
}
.navigation
{
	position: relative;
}

</style>
<div class="header">
	<div class="wrap">
		<div class="logo">
			<h1><a href='<?php echo url::base("{site-slug}");?>' style='color:inherit;'><?php echo $siteName;?></a></h1>
		</div>

		<div class='navigation'>
			<div class='search-box'>
			<form method='get' action='<?php echo url::base("{site-slug}/carian");?>'>
				Carian : <input id='q' name='q' value="<?php echo request::get('q');?>" type='search' />
			</form>
			</div>
		<ul class='nav'>
		<?php
		$componentChildR	= model::load("site/menu")->componentChild();

		if($menuR)
		{
			## prepare list of menu under component.
			$componentMenu	= Array(
							1=>Array("page@index"),
							2=>Array("main@index"),
							3=>Array("activity@index","activity@index","activity@view"),
							4=>Array("gallery@albumView","gallery@index","gallery@index_month"),
							5=>Array("main@contact"),
							6=>Array("blog@article","blog@view")
									);

			$menuNo = 0;
			foreach($menuR as $row)
			{
				$menuNo++;

				## custom hardcoded top menu.
				if($menuNo == count($menuR))
				{
					$faqHref	= url::base("{site-slug}/soalan-lazim");
					$cssActive	= controller::getCurrentMethod() == "faq"?"active":"";
					echo "<li><a href='$faqHref' class='$cssActive'>Soalan Lazim</a></li>";
				}

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

				## prepare the active menu css.
				$cm	= $controller."@".$method;

				$cssActive	= "";
				if(isset($componentMenu[$component]) && in_array($cm,$componentMenu[$component]))
				{
					$cssActive	= "active";
				}

				$dropdownIcon	= isset($childPageR[$pageID]) || isset($componentChildR[$component])?'<span><i class="fa fa-sort-asc"></i></span>':"";
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


				if(isset($componentChildR[$component]))
				{
					echo "<div>";
					echo '<div class="nav-column"><div class="menu-block">';
					echo "<ul class='clearfix'>";
					$no	= 1;
					foreach($componentChildR[$component] as $headername=>$rowR)
					{

						echo "<h3>$headername</h3>";
						foreach($rowR as $row)
						{
							$href	= $row[1] == "#"?"#":url::base("{site-slug}/".$row[1]);
							echo "<li><a href='$href'>$row[0]</a></li>";
							$no++;
						}
					}

					echo "</ul>";
					echo "</div></div>";
					echo "</div>";
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