/* 
	Author: Axl Mulat
	Site: http://istockphp.com
*/
jQuery(function($) {
	
	var scrollBlockHeight = $("div#scroll_block").height(); // get height
	$("div#scrollbar_holder").height(scrollBlockHeight); // set height for scroll holder
	
	var contentHeight 	= $("div#content").height(); // get content div height
	// formula custom scroll bar 
	var scrollbarHeight = ( scrollBlockHeight / contentHeight ) * scrollBlockHeight; 
 	
	if(contentHeight < scrollBlockHeight) { // if the content is short, hide the scrollbar
		$("div.scrollbar").css('display', 'none');
		$("div#scrollbar_holder").css('display', 'none');
	} else {
		$("div.scrollbar").height(scrollbarHeight)
	  }
	  
	/* trigger 1: start function drag */
	$("div.scrollbar").draggable({ 
			axis: 'y',
			containment: 'parent',
			drag: function() {
				var getPositionTop 	= parseInt($(this).css('top')); // get css top
				// formula for content up 
				var scrollTopNew 	= ( getPositionTop / scrollBlockHeight) * (contentHeight); 
				//console.log(getPositionTop); // logs
				$("div#scroll_content").css("top", '-'+scrollTopNew+'px'); // up the content
			}
	}); 
	/* trigger 1: end function drag */
	
	/* trigger 2: function scroll */
	var getmoduloHeight = scrollBlockHeight - scrollbarHeight; // scrollBlock - scrollbar

	// get total height content 
	var formulateHeight = ( getmoduloHeight / scrollBlockHeight) * (contentHeight); 
	
	$('#scroll_block').bind("mousewheel", function (event, delta) {
		//console.log(delta); // delta scroll trigger
		var getPositionTop		= parseInt( $('div.scrollbar').css('top')); // get css top
		var scrollTopNew		= getPositionTop - (delta * 10); // with delta
		var scrollTopContent	= ( scrollTopNew / scrollBlockHeight) * (contentHeight);
		
		//console.log(getPositionTop);
		if(scrollTopNew < 0) { // if rich the top content, return false, and set default
			$("div.scrollbar").css("top", "0px" );
			$("div#scroll_content").css("top", "0px" );
			return false;	
		}
		if(scrollTopContent > formulateHeight) { // if rich the bottom content, return false, and set default
			$("div.scrollbar").css("top", getmoduloHeight );
			$("div#scroll_content").css("top", '-'+formulateHeight+'px');
			return false;	
		}
		
		$("div#scroll_content").css("top", '-'+scrollTopContent +'px'); // up the content
		$("div.scrollbar").css("top", scrollTopNew ); // down the scroll bar
	}); 
	/* trigger 2: end function scroll */
	
	/* trigger 3: click scrollbar holder, scroll start */
	$("div#scrollbar_holder").click(function(e) { // add event
	 	 
	  	var getPositionTop 	= parseInt($("div.scrollbar").css('top')); // get css top
	  
	  //var pageX 			= e.pageX - this.offsetLeft;
		var pageY 			= e.pageY - this.offsetTop;
		
		var topWithScroll 	= getPositionTop + scrollbarHeight; // get height scrolltop + scrollbar Height
		var positionTop 	= pageY - scrollbarHeight; // pagey - scrollbar Height =  to get the get height scrolltop
		
		//console.log(pageY);
		var pageY_adjust = (pageY - 20); // adjust
		if(pageY_adjust < getPositionTop) { // click up the scroll bar
				
				$("div.scrollbar").stop(true, false).animate({ "top" :   pageY-40 }, 'fast'); 
				var getPositionTop_click = pageY-40;
				
				if(getPositionTop_click < 0) { // if user click the top level of the scroll, set to animate to top
					
					$("div.scrollbar").stop(true, false).animate({ "top" :   0 }, 'fast'); 
					$("div#scroll_content").stop(true, false).animate({ "top" :   0 }, 'fast'); 
				
				} else { 
					
					var scrollTopNew 	= ( getPositionTop_click / scrollBlockHeight) * (contentHeight); 
					$("div#scroll_content").stop(true, false).animate({ "top" :   '-'+scrollTopNew+'px' }, 'fast'); 
					
					}
		} else { // click down the scroll bar
			if(pageY > topWithScroll) {
					
					$("div.scrollbar").stop(true, false).animate({ "top" : positionTop }, 'fast');
					var getPositionTop_click = positionTop;
					
					if(pageY > scrollBlockHeight) { // if user click the bottom level of the scroll, set to animate to bottom
						
						$("div.scrollbar").stop(true, false).animate({ "top" : getmoduloHeight }, 'fast'); // getmoduloHeight from scrollbar var
						$("div#scroll_content").stop(true, false).animate({ "top" :   '-'+formulateHeight+'px' }, 'fast');  // formulateHeight from scrollbar var
					
					} else {
						
						var scrollTopNew 	= ( getPositionTop_click / scrollBlockHeight) * (contentHeight); 
						$("div#scroll_content").stop(true, false).animate({ "top" :   '-'+scrollTopNew+'px' }, 'fast'); 
					
					}
			}
		  }
		
	e.preventDefault(); // like return false
	});
	/* trigger 3: click scrollbar holder, scroll end */
}); // jQuery End