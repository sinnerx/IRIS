<h1><?php echo $pageTitle;?></h1>
<?php if($pageImage):?>
<div><img id='pageImage' src='<?php echo model::load("api/image")->buildPhotoUrl($pageImage,"page");?>' /></div>
<?php endif;?>
<div><?php echo $pageText;?></div>
<script type="text/javascript">
function imgResize(ele)
{
    var width = jQuery(ele).outerWidth();
    
    if(width > 320)
        jQuery(ele).attr("width","660px");
}

if(jQuery('#pageImage')[0].complete)
{
    imgResize('#pageImage');

}
else
{
    //if image loaded. check if width more than 50% maximize it. else. keep it.
    jQuery("#pageImage").load(function()
    {
        imgResize(this);
    });
}
