;(function(){

	$('#button-search').on('click', function(e){
		
		e.preventDefault();
		$(this).closest(".search-wrapper").toggleClass('opened').find('input').focus();		

	});

	$(document).ready(function(){
	  $('.bxslider').bxSlider({
	  	controls: false,
	  	mode: 'fade'
	  });
	  
	});

	$('#menu-button').on('click', function(){
		$('.nav').slideToggle();
	});


	$(function(){
		    if( $('.arrow-content')[0] ) {
		        var el = $('.arrow-content '),
		            el_top = el.offset().top;

		        el.addClass('relative');

		        $(window).scroll(function() {


		            if (!el.hasClass('fixed') && $(this).scrollTop() >= ( el_top - 263) ) {
		                el.removeClass('absolute');
		                el.addClass('fixed');
		                
		            } else if (el.hasClass('fixed') && $(this).scrollTop() <= ( el_top - 263 ) ) {
		                el.removeClass('fixed');
		                el.addClass('absolute');
		                
		            }
		        });
		    }
		}())


}());