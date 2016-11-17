jQuery(document).ready (function () {
selectnav('nav');  
$(window).load(function(){
$('.flexslider').flexslider({
animation: "slide"
});
});  
	$().UItoTop({ easingType: 'linear' });
      $('.tooltips').tooltip();    
	  		$("ul.sortbylabs").each(function() {
			var source = $(this);
			var destination = $("ul.datasort[data-id=" + $(this).attr("data-id") + "]");
				$(window).load(function() {
					destination.isotope({
						itemSelector: "li",
              filter: '.random_product',
						layoutMode : "fitRows"  
					});
					source.find("a").click(function(e) {
						e.preventDefault();
						var $this = $(this),
							filter = $this.parent().attr("data-option-value");
						source.find("li.active").removeClass("active");
						$this.parent().addClass("active");
						destination.isotope({
							filter: filter
						});
						self.location = "#" + filter.replace(".","");
						return false;
					});
				});
		});	
});
sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
$(function() {
    // grab the initial top offset of the navigation
    var sticky_navigation_offset_top = $('#storesl').offset().top;
    // our function that decides weather the navigation bar should have "fixed" css position or not.
    var sticky_navigation = function(){
        var scroll_top = $(window).scrollTop(); // our current vertical position from the top
        // if we've scrolled more than the navigation, change its position to fixed to stick to top,
        // otherwise change it back to relative
        if (scroll_top > sticky_navigation_offset_top) {
            $('#storesl').css({'position': 'fixed', 'z-index': '9999', 'top': '0', 'opacity': '0.9'});
        } else {
            $('#storesl').css({'position': 'relative', 'opacity': '1'});
        }  
    };
    // run our function on load
    sticky_navigation();
    // and run it again every time you scroll
    $(window).scroll(function() {
         sticky_navigation();
    });
});  