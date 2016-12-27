<style type="text/css">
	
.accordion
{
	padding:5px;
	border-top:1px dashed #cacaca;
	color: #00406a;
	cursor: pointer;
}
.accordion:hover
{
	font-weight: bold;
}
.accordion.active
{
	font-weight: bold;
}
.block-content .container
{
	padding:10px;
	padding-bottom:30px;
	display: none;
	padding-top:0px;
	font-size:0.9em;
}
.container .content
{
	width:inherit;
	height: inherit;
}

</style>
<h3 class="block-heading">SOALAN LAZIM</h3>
<div class="block-content clearfix">
	<div class="accordion" data-no='1' id="section1"><span></span>1. Bagaimanakah cara untuk saya memohon Pusat Internet 1Malaysia (PI1M) di kawasan saya?</div>
	<div class="container">
	    <div class="content">
	        <p>Pemilihan lokasi Calent adalah berdasarkan kepada tinjauan dan lawatan tapak mengikut garis panduan yang telah ditetapkan oleh SKMM dengan kerjasama agensi Kerajaan pusat dan negeri yang berkaitan.</p>
	        <p>Perlantikan pemberi perkhidmatan bagi melaksanakan Calent adalah melalui proses tender terbuka kepada pemegang-pemegang lesen di bawah Akta Komunikasi dan Multimedia (AKM 1998).</p>
	    </div>
	</div>
	<div class="accordion" data-no='2' id="section2"><span></span>2. Bagaimanakah kaedah pelaksanaan projek Calent?</div>
	<div class="container">
	    <div class="content">
	        <p>Proses pelaksanaan Calent adalah melalui kaedah tender terbuka mengikut peraturan-peraturan Pemberian Perkhidmatan Sejagat (USP). Sila rujuk <a href="http://www.skmm.gov.my" target="_blank">www.skmm.gov.my</a> dari semasa ke semasa bagi pemberitahuan tender.</p>
	    </div>
	</div>
	<div class="accordion" data-no='3' id="section3"><span></span>3. Adakah terdapat syarat-syarat khusus untuk membida projek Calent?  Adakah orang awam boleh memohon untuk membuka Calent ini?</div>
	<div class="container">
	    <div class="content">
	        <p>Pihak pembida hendaklah merupakan pemegang lesen Pemberi Perkhidmatan Rangkaian Individu (NSP(I)),Pemberi Perkhidmatan Kemudahan Rangkaian Individu (NFP(I)) dan Pemberi Perkhidmatan Aplikasi Kelas (ASP(C)) di bawah Akta Komunikasi dan Multimedia 1998. Sila rujuk kepada Bahagian Pelesenan SKMM untuk keterangan lanjut.</p>
	    </div>
	</div>
	<div class="accordion" data-no='4' id="section4"><span></span>4. Apakah kemudahan dan perkhidmatan yang disediakan di Pusat Internet 1Malaysia (PI1M)?</div>
	<div class="container">
	    <div class="content">
	        <p>Perkhidmatan yang disediakan adalah capaian internet jalur lebar berkelajuan tinggi, perkhidmatan Wi-Fi bagi capaian penduduk sekitar, kemudahan IT terkini seperti 20 buah komputer dan perisian, perkhidmatan faks, pencetak, program latihan ICT dan pembangunan modal insan dan aktiviti berkaitan yang akan diuruskan oleh pengurus dan pembantu pengurus.</p>
	    </div>
	</div>
	<div class="accordion" data-no='5' id="section5"><span></span>5. Siapakah yang boleh mengunjungi Calent?</div>
	<div class="container">
	    <div class="content">
	        <p>Penggunaan Calent adalah terbuka kepada semua lapisan masyarakat tanpa mengira usia.</p>
	    </div>
	</div>
	<div class="accordion" data-no='6' id="section6"><span></span>6. Adakah saya perlu mendaftar untuk menggunakan kemudahan di Calent ?</div>
	<div class="container">
	    <div class="content">
	        <p>Pengguna digalakkan untuk mendaftar sebagai ahli Calent bagi menikmati kadar yang lebih murah bagi setiap kemudahan dan perkhidmatan yang disediakan.</p>
	    </div>
	</div>
	<div class="accordion" data-no='7' id="section7"><span></span>7. Adakah saya akan dikenakan caj sekiranya menggunakan kemudahan atau perkhidmatan yang disediakan di Calent?</div>
	<div class="container">
	    <div class="content">
	        <p>Terdapat kemudahan dan perkhidmatan yang dikenakan dengan kadar caj yang rendah dan berpatutan.</p>
	    </div>
	</div>
	<div class="accordion" data-no='8' id="section8"><span></span>8. Saya berminat untuk mengikut program latihan ICT di Calent. Berapakah kadar yang dikenakan bagi setiap program latihan?</div>
	<div class="container">
	    <div class="content">
	        <p>Tiada bayaran akan dikenakan ke atas setiap program latihan yang disediakan. Anda dinasihatkan untuk berhubung dengan pengurus Calent yang berhampiran untuk keterangan lanjut.</p>
	    </div>
	</div>
	<div class="accordion" data-no='9' id="section9"><span></span>9. Apakah perbezaan di antara Calent dengan cybercafe sediada?</div>
	<div class="container">
	    <div class="content">
	        <p>Setiap penggunaan komputer Calent adalah dipantau oleh Sistem Pemantauan Berpusat dan pengurus bagi memastikan tiada sebarang penyalahgunaan. Selain itu, terdapat juga kempen kesedaran dan panduan penggunaan internet kepada pengguna.</p>
	    </div>
	</div>
</div>

<script type="text/javascript">
jQuery(".accordion").click(function()
{
	var n	= jQuery(this).data("no");
	jQuery(".block-content .container").slideUp();
	jQuery(".accordion").removeClass("active");
	jQuery("#section"+n).addClass("active");
	jQuery(jQuery(".block-content .container")[n-1]).slideDown();
});

</script>