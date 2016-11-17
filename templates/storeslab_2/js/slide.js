$(function(){
 $('#slider2')
  .anythingSlider({
   mode:         'horizontal',
   resizeContents      : true,
   expand              : true,
   autoPlay            : true,
   width               : 1168,
	 height              : 501,
	 buildArrows         : true,
	 delay               : 5000,
   animationTime       : 1500, 
   navigationFormatter : function(i, panel){
    return ['text1', 'text2', 'text3'][i - 1];
   }
  })
  .anythingSliderFx({
   '.fade' : [ 'fade' ],
   inFx : {
    '.textSlide h3'  : { left : '0px', duration: 800, easing : 'easeOutExpo' },
    '.textSlide img' : { top : '0px', duration: 1200, easing : 'easeOutExpo' },
    '.textSlide li'  : { opacity: 1, left : 0, duration: 400 },
    '.quoteSlide'    : { top : 0, duration: 500, easing : 'easeOutElastic' },
    '.expand  p'     : { left : '0px', duration: 600, easing : 'easeOutBounce' },
    '.expand img'    : { left : '0px', duration: 800, easing : 'easeOutExpo' }
   },
   outFx : {
    '.textSlide h3'       : { left : '1168px', duration: 1200, easing : 'easeOutExpo' },
    '.textSlide img'      : { top : '501px', duration: 1200, easing : 'easeOutExpo' },
    '.textSlide li'  : { opacity: 0, left : '500px',  duration: 350 },
    '.quoteSlide:first'   : { top : '-501px', duration: 350 },
    '.quoteSlide:last'    : { top : '501px', duration: 300 },
    '.expand  p'          : { left : '1168px', duration: 1200, easing : 'easeOutBounce' },
     '.expand img'        : { left : '1168px', duration: 1200, easing : 'easeOutExpo'}
   }
  });
});