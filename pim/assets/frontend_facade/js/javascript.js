$(document).ready(function() {
	$("div.panel_button").click(function(){
		$("div#panel").animate({
			height: "650px"
		})
		.animate({
			height: "630px"
		}, "fast");
		$("div.panel_button").toggle();
	
	});	
	
   $("div#hide_button").click(function(){
		$("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
   
   $("div#hide_button_top").click(function(){
		$("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
	
});