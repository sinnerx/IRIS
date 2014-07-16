<h3 class="block-heading">
<a href='#' name='title'></a>
<?php
echo model::load("template/frontend")
->buildBreadCrumbs(Array(
				Array("404")
						));
?></h3>
<div class="block-content clearfix">
	<div class="page-content">
	<?php
	if(request::get("tiada_akses"))
	{
		echo "Anda tiada akses ke page ini, sila login dahulu.";
	}
	else
	{
		echo "Tidak dapat menjumpai page yang anda cari.";
	}

		?>
	</div>
</div>