<style type="text/css">
.msgbox
{
    padding:10px;
    position: relative;
    top:-20px;
    color: white;
}
.msgbox.success
{
    background:#0081d7;
    border:1px dashed #005a97;
}
.msgbox.error
{
    background: #c91d1d;
    border:1px solid #712626;
    box-shadow: 0px 0px 10px #731111;
}

.label.label-danger
{
    background: #ec4848;
    color:white;
    display: inline-block;
    padding:4px;
    float:right;
    margin-right:30px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px #731111;
}

</style>
<script type="text/javascript">
jQuery(document).ready(function()
{
    setTimeout(function()
    {
        if(!jQuery("#mailmessage")[0])
        {
            return;
        }
        var container = jQuery(document.body);
        var scrollTo = jQuery('#mailmessage'); //if no element is not found, it won't scroll. it's ok it will give error.

        //scroll top first.
        container.scrollTop(0);

        //scroll to designated
        container.animate({scrollTop:scrollTo.offset().top - container.offset().top + container.scrollTop()});

    },500);
    
});

</script>
<script type="text/javascript" src='<?php echo url::asset("_templates/js/contact-form.js");?>'></script>
<h3 class="block-heading">HUBUNGI KAMI</h3>
<div class="block-content clearfix">

    <div class="page-content">
      <div class="page-sub-wrapper single-page clearfix">
        <div class="content-page clearfix"> 
        <div class="left-contact-info">
        <div class="heading-contact-page">Maklumat kami untuk dihubungi </div>
        <span class="heading-contact">Emel Laman</span>
        <span class="info-contact"><?php echo $row['siteInfoEmail']?strtoupper($row['siteInfoEmail']):"-";?></span>
        <span class="heading-contact">No. Telefon</span>
        <span class="info-contact"><?php echo $row['siteInfoPhone']?:"-";?></span>
        <span class="heading-contact">No. Fax</span>
        <span class="info-contact"><?php echo $row['siteInfoFax']?:"-";?></span>
        <span class="heading-contact">Alamat</span>
        <span class="info-contact">
        <?php echo nl2br($row['siteInfoAddress']?:"-");?>
        </span>
        </div>
        
        <div class="right-contact-pic">
        <?php if($row['siteInfoImage']):?>
          <img src='<?php echo url::asset("frontend/images/siteImage/".$row['siteInfoImage']);?>' alt=""/>
        <?php endif;?>
        </div>
        <div class="clr"></div>
        <div class="contact-form">
        <?php echo flash::data();?>
            <div class="contact-caption">
            Jika anda mempunyai sebarang aduan atau pertanyaan, sila hubungi kami menerusi borang online di bawah ini. Kami mengalu-alukan sebarang maklum balas daripada anda 
            </div>
            <form id="contact-form" action="" method="post">
                <div>
                    <label>
                    <span id='errorscroll'>Kategori: *</span>
                    <?php echo form::radio("siteMessageCategory",$categoryNameR,null,null,"<div style='display:inline;'> {content} </div>");?>
                    <?php echo flash::data("siteMessageCategory");?>
                    </label>
                </div>
                <div>
                    <label>
                    <span>Nama: *</span>
                    <?php echo form::text("contactName","placeholder='Sila masukkan nama anda' tabindex='1'");?>
                    <?php echo flash::data("contactName");?>
                    </label>
                </div>
                <div>
                    <label>
                    <span>Email: *</span>
                    <?php echo form::text("contactEmail","placeholder='Sila masukkan alamat email' tabindex='2'");?>
                    <?php echo flash::data("contactEmail");?>
                    </label>
                </div>
                <div>
                    <label>
                    <span>Telefon: *</span>
                    <?php echo form::text("contactPhoneNo","placeholder='Sila masukkan nombor telefon anda. Contohnya : 0126966111.' tabindex='3'");?>
                    <?php echo flash::data("contactPhoneNo");?>
                    </label>
                </div>
                <div>
                    <label>
                        <span>Tajuk :</span>
                        <?php echo form::text("messageSubject","placeholder='Sila masukkan tajuk ' tabindex='4'");?>
                        <?php echo flash::data("messageSubject");?>
                        <!-- <input placeholder="Message Subject" type="tel" tabindex="3" required> -->
                    </label>
                </div>
                <div>
                    <label>
                    <span>Mesej: *</span>
                    <?php echo form::textarea("messageContent","placeholder='Taip mesej anda di sini' tabindex='5'");?>
                    <?php echo flash::data("messageContent");?>
                    </label>
                </div>
                <div>
                    <button onclick='document.getElementById("theForm").submit();   ' name="submit" type="submit" id="contact-submit">Hantar</button>
                </div>
            </form>
        </div>
          </div>
        </div>
    </div>
</div>
<?php /*
<div class='contact-form'>
<form method="post">
    <h3 class='block-heading'>ATAU ISI RUANGAN DI BAWAH INI</h3>
    <?php echo flash::data();?>
    <table width='100%'>
        <tr>
            <td width='20%'>
                <div>Nama</div>
                <div><?php echo form::text("contactName");?></div>
            </td>
            <td width='80%' rowspan="3" valign="top">
                <div>Message</div>
                <div><?php echo form::text("messageSubject","placeholder='Subject' style='width:100%;'");?></div>
                <div><?php echo form::textarea("messageContent","placeholder='Mesej Anda' style='width:100%;height:150px;'");?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div>Phone No</div>
                <div><?php echo form::text("contactPhoneNo");?></div>
            </td>
        </tr>
        <tr>
        <td>
            <div>Email</div>
            <div><?php echo form::text("contactEmail");?></div>
        </td>
        <tr>
            <td><?php echo form::submit("Hantar");?></td>
        </tr>
        </tr>
    </table>
</form>
</div>*/?>