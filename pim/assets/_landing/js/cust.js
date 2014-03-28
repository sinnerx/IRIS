var cns=0;
$(window).resize(function(){	
var w = $(window).width();
if(w > 1019 ) {$('#block_navigation > ul').css('display', 'block')}
if (w <= 1019 && cns==0){$('#block_navigation > ul').css('display', 'none')}
if (w <= 1019 && cns==1){$('#block_navigation > ul').css('display', 'block')}
});
$(document).ready(function(){
var w = $(window).width();
if(w > 1019) {$('#block_navigation > ul').css('display', 'block')}
else {$('#block_navigation > ul').css('display', 'none');
 }});
$('#pull').click(function()
{
if(cns==0){$('#block_navigation > ul').slideDown(),
cns=1;
}
else{$('#block_navigation > ul').slideUp(),
cns=0;
}
}
);
$(document).ready(function(){
var w = $(window).width();
if(w < 1019) {$('#pull').css('display', 'block')}
else {$('#pull').css('display', 'none');
 }});
 $(window).resize(function(){
var w = $(window).width();
if(w < 1019) {$('#pull').css('display', 'block')}
else {$('#pull').css('display', 'none');
 }});

 
 
 
 
 
 