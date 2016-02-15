<style type="text/css">

#nav.navigation .colmn_wrap
{
	height: 480px;
}

#nav.navigation > li:nth-child(3) ul
{
	top: -242px;
}

</style>
<ul class="navigation" style="display: block;"  id="nav">
	<li class="selected"><a href="<?php echo url::base();?>">Utama</a></li>
    <li><a href="mengenai-kami">Mengenai Kami</a></li>
	<li><a href="#"  class="ert">Senarai Pusat <span class="main_links_span">+</span></a>
	<!------------DROPDOWN_MENU_START_LEVEL_1------------>
		<ul>
			<?php
			foreach($state as $stateID => $stateName)
			{
				if(!isset($stateR[$stateID]))
				{
					continue;
				}
				$totalSite	= isset($stateR[$stateID]['total'])?$stateR[$stateID]['total']:0;
				$stateLabel	= $stateName." ($totalSite)";

				$plus		= $totalSite > 0?"<span class='main_links_span'>+</span>":"";
				echo "<li><a href='#'>$stateLabel $plus</a>";

				if(isset($stateR[$stateID]['records']))
				{
					echo '<div class="colmn_wrap">';
					echo '<div id="content_7" class="content">';
					echo "<div class='typography_3_colm'>";
					echo "<div class='colm_3_container'>";

					$no	= 1;
					## prepare list per 5
					$opened	= false;
					foreach($stateR[$stateID]['records'] as $row)
					{
						## open wrapper every five records.
						if($no == 1)
						{
							$opened	= true;
							echo "<div class='colmn_3_fullwidth'><ol class='some_links'>";
						}

						$siteName	= ucwords($row['siteName']);
						$siteSlug	= strtolower($row['siteSlug']);

						echo "<li><a target='_blank' href='".url::base("$siteSlug")."'>$siteName</a></li>";

						$no++;

						## close ol and div wrapper
						if($no == 6)
						{
							$opened = false;
							$no	= 1;
							echo "</ol></div>";
						}
					}

					if($opened)
					{
						## close ol and div wrapper.
						echo "</ol></div>";
					}

					## close main list wrapper.
					echo "</div></div></div></div>";
				}

				echo "</li>";
			}

			?>
			<?php
			/*
			Example format.
			<li><a href="#">Perlis (3)</a></li>
			<li><a href="#">Kedah (1)</a></li>
			<li><a href="#">Negeri Sembilan (6) <span class="main_links_span">+</span></a>
				<!------------DROPDOWN_MENU_START_LEVEL_2------------>
				<div class="typography_3_colm">
			<div class="colm_3_container">
				<div class="colmn_3_fullwidth">
					<ol class="some_links">
						<li><a>Pi1M Bambangan</a></li>
						<li><a>Pi1M Batu Payung, Tawau</a></li>
						<li><a>Pi1M Bingkor , Keningau</a></li>
						<li><a>Pi1M Bintang Mas , Beluran</a></li>
						<li><a>Pi1M Kg Desa Aman , Ranau</a></li>
					</ol>
				</div>
				<div class="colmn_3_fullwidth">
					<ol class="some_links">
						<li><a>Pi1M Kg Kadazan , Kunak</a></li>
						<li><a>Pi1M Kg Lohan , Ranau</a></li>
						<li><a>Pi1M Kg Lormalong , Kunak</a></li>
						<li><a>Pi1M Kg Sogo Sogo , Tongod</a></li>
						<li><a>Pi1M Kg. Airport, Kunak</a></li>
					</ol>
				</div>
				<div class="colmn_3_fullwidth">
					<ol class="some_links">
						<li><a href="#">Pi1M Kg. Biau, Bongawan</a></li>
						<li><a href="#">Pi1M Kg. Bugaya</a></li>
						<li><a href="#">Pi1M Kg. Inobong</a></li>
						<li><a href="#">Pi1M Kg. Bundu, Kuala Penyu</a></li>
						<li><a href="#">Pi1M Kg. Langkuas</a></li>
					</ol>
				</div>
			</div>
				</div>
				<!------------DROPDOWN_MENU_END_LEVEL_2------------>
			</li>
			<li><a href="#">Johor (12)</a></li>
			<li><a href="#">Sabah (41)</a></li>
          <li><a href="#">Sarawak (4)</a></li>
          */?>
		</ul>
		<!------------DROPDOWN_MENU_END_LEVEL_1------------>
	</li>
	<li><a href="hubungi-kami">Hubungi Kami</a></li>
</ul>
