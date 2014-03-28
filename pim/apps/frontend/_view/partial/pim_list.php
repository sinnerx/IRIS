<?php
if(count($siteListR) > 0):
foreach($siteListR as $siteID=>$row)
{
	$slug	= $row['siteSlug'];
	$name	= ucwords($row['siteName']);
	$state	= $stateR[$row['stateID']];
	$href	= url::base("$slug");
	echo "<li><a href='$href'>$name, $state</a></li>";
}
endif;
?>
<!-- Example 
<li><a href="index.asp?cbcsite=2200123">Pi1M Babagon</a></li>
<li><a href="index.asp?cbcsite=2200124">Pi1M Bambangan</a></li>
<li><a href="index.asp?cbcsite=2200026">Pi1M Batu Payung, Tawau, Sabah</a></li>
<li><a href="index.asp?cbcsite=2000006">Pi1M Bingkor , Keningau , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000001">Pi1M Bintang Mas , Beluran , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000011">Pi1M Kg Desa Aman , Ranau , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000009">Pi1M Kg Kadazan , Kunak , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000012">Pi1M Kg Lohan , Ranau , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000008">Pi1M Kg Lormalong , Kunak , Sabah</a></li>
<li><a href="index.asp?cbcsite=2000015">Pi1M Kg Sogo Sogo , Tongod , Sabah</a></li>
<li><a href="index.asp?cbcsite=2200010">Pi1M Kg. Airport, Kunak,Sabah</a></li>
<li><a href="index.asp?cbcsite=2200001">Pi1M Kg. Apin-Apin, Keningau, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200025">Pi1M Kg. Balong Kokos, Tawau, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200111">Pi1M Kg. Belatik</a></li>
<li><a href="index.asp?cbcsite=2200019">Pi1M Kg. Biau, Bongawan, Papar, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200006">Pi1M Kg. Bongkud, Ranau, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200117">Pi1M Kg. Bugaya</a></li>
<li><a href="index.asp?cbcsite=2200020">Pi1M Kg. Bundu, Kuala Penyu, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200002">Pi1M Kg. Bunsit, Keningau, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200012">Pi1M Kg. Duvanson Ketiau, Putatan, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200009">Pi1M Kg. Hampilan, Kunak, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200116">Pi1M Kg. Inobong</a></li>
<li><a href="index.asp?cbcsite=2200018">Pi1M Kg. Kuala, Papar, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200112">Pi1M Kg. Langkuas</a></li>
<li><a href="index.asp?cbcsite=2200013">Pi1M Kg. Malanggan Baru,Tamparuli, Sabah</a></li>
<li><a href="index.asp?cbcsite=2200004">Pi1M Kg. Malima Sook, Keningau, Sabah</a></li>-->