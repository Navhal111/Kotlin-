(function($) {
  $.fn.letterDrop = function() {
    // Chainability
    return this.each(function() {
      var obj = $(this);

      var drop = {
        arr: obj.text().split(""),

        range: {
          min: 1,
          max: 9
        },

        styles: function() {
          var dropDelays = "\n",
            addCSS;

          for (i = this.range.min; i <= this.range.max; i++) {
            dropDelays += ".ld" + i + " { animation-delay: 1." + i + "s; }\n";
          }

          addCSS = $("<style>" + dropDelays + "</style>");
          $("head").append(addCSS);
        },

        main: function() {
          var dp = 0;
          obj.text("");

          $.each(this.arr, function(index, value) {
            dp = dp.randomInt(drop.range.min, drop.range.max);

            if (value === " ") value = "&nbsp";

            obj.append(
              '<span class="letterDrop ld' + dp + '">' + value + "</span>"
            );
          });
        }
      };

      Number.prototype.randomInt = function(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
      };

      // Create styles
      drop.styles();

      // Initialise
      drop.main();
    });
  };
})(jQuery);

$('#logo').css("display","none");
setTimeout(function(){ $('#logo').css({"display": "inline"}); $("#logo").letterDrop(); }, 4000);

jQuery(function($) {'use strict';

	//Responsive Nav
	$('li.dropdown').find('.fa-angle-down').each(function(){
		$(this).on('click', function(){
			if( $(window).width() < 768 ) {
				$(this).parent().next().slideToggle();
			}
			return false;
		});
	});

	//Fit Vids
	if( $('#video-container').length ) {
		$("#video-container").fitVids();
	}

	//Initiat WOW JS
	new WOW().init();

	// portfolio filter
	$(window).load(function(){

		$('.main-slider').addClass('animate-in');
		$('.preloader').remove();
		//End Preloader

		if( $('.masonery_area').length ) {
			$('.masonery_area').masonry();//Masonry
		}

		var $portfolio_selectors = $('.portfolio-filter >li>a');

		if($portfolio_selectors.length) {

			var $portfolio = $('.portfolio-items');
			$portfolio.isotope({
				itemSelector : '.portfolio-item',
				layoutMode : 'fitRows'
			});

			$portfolio_selectors.on('click', function(){
				$portfolio_selectors.removeClass('active');
				$(this).addClass('active');
				var selector = $(this).attr('data-filter');
				$portfolio.isotope({ filter: selector });
				return false;
			});
		}

	});


	$('.timer').each(count);
	function count(options) {
		var $this = $(this);
		options = $.extend({}, options || {}, $this.data('countToOptions') || {});
		$this.countTo(options);
	}

	// Search
	$('.fa-search').on('click', function() {
		$('.field-toggle').fadeToggle(200);
	});

	// Contact form
	var form = $('#main-contact-form');
	form.submit(function(event){
		event.preventDefault();
		var form_status = $('<div class="form_status"></div>');
		$.ajax({
			url: $(this).attr('action'),
			beforeSend: function(){
				form.prepend( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> Email is sending...</p>').fadeIn() );
			}
		}).done(function(data){
			form_status.html('<p class="text-success">Thank you for contact us. As early as possible  we will contact you</p>').delay(3000).fadeOut();
		});
	});

	// Progress Bar
	$.each($('div.progress-bar'),function(){
		$(this).css('width', $(this).attr('data-transition')+'%');
	});

	if( $('#gmap').length ) {
		var map;

		map = new GMaps({
			el: '#gmap',
      lat: 21.1892539,
			lng: 72.7876768,
			scrollwheel:false,
			zoom: 16,
			zoomControl : true,
			panControl : true,
			streetViewControl : true,
			mapTypeControl: true,
			overviewMapControl: true,
			clickable: false,
		});

		map.addMarker({
      lat: 21.1892539,
			lng: 72.7876768,
      title:"Pistalix",
      infoWindow:{
        content:'<p><address>1004, White Orchid, near Shell petrol pump, L.P Savani road ,<br>Adajan, Surat, Gujarat 395009 </address></p>'
      },
			animation: google.maps.Animation.DROP,
			verticalAlign: 'bottom',
			horizontalAlign: 'center',
			backgroundColor: '#3e8bff',
		});
	}

});
