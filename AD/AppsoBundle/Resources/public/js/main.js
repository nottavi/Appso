(function($){
	$(document).ready(function() {
		if ( $('.fancybox').length ) {
			$('.fancybox').fancybox({
				width: 1000,
				padding: 0,
				closeBtn: false
			});
		}

		/* Home Slider */
		if ( $('#home-slider ul').length ) {
			$('#home-slider ul').carouFredSel({
				responsive: true,
				prev: {
					button: '#home-slider .slider-nav .prev'
				},
				next: {
					button: '#home-slider .slider-nav .next'
				},
				scroll: {
					timeoutDuration: 5000
				}
			});
		}

		/* Font-size global */
		$('#font-size').on('click', function(){
			$('html').toggleClass('big-font');
			$(this).toggleClass('big');
		});

		/* inFieldLabels */
		if ( $('label.infield').length ) {
            $('label.infield').inFieldLabels();
		}
	});
})(jQuery);