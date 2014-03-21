var $news = jQuery.noConflict();
$news(function () {
     // start the ticker 
	$news('#js-news').ticker();
	
	// hide the release history when the page loads
	$news('#release-wrapper').css('margin-top', '-' + ($news('#release-wrapper').height() + 20) + 'px');

	// show/hide the release history on click
	$news('a[href="#release-history"]').toggle(function () {	
		$news('#release-wrapper').animate({
			marginTop: '0px'
		}, 600, 'linear');
	}, function () {
		$news('#release-wrapper').animate({
			marginTop: '-' + ($news('#release-wrapper').height() + 20) + 'px'
		}, 600, 'linear');
	});	
	
	$news('#download a').mousedown(function () {
		_gaq.push(['_trackEvent', 'download-button', 'clicked'])		
	});
});

