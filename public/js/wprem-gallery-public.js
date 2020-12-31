(function($) {
    'use strict';

    jQuery(document).ready(function($) {
        $('.wprem-gallery-single--').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.wprem-gallery',
            adaptiveHeight: true
        });
        $('.wprem-gallery--').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.wprem-gallery-single',
            dots: true,
            centerMode: true, 
            focusOnSelect: true,
            prevArrow: '<div class="slick-prev">d</div>',
            nextArrow: '<div class="slick-next">b</div>',
        });

        $('.wprem-gallery').on("beforeChange",function(event){
        	$(".slick-arrow").hide();
        });

        $('.wprem-gallery').on("setPosition",function(event){
        	$(".slick-arrow").show();
        });

        /* when clicking a thumbnail */
        //$(".thumbnail").click(function() {
        //    $('.carousel').carousel($(".thumbnail").index(this));
        //});

		$('.wprem-gallery-container a').simpleLightbox();
        $('.wprem-gallery-container a').on("click", function(){
            $(".mfp-close").trigger("click");
        });
        //$(document).on('click', '[data-toggle="lightbox"]', function(event) {
        //    event.preventDefault();
        //    $(this).ekkoLightbox();
        //    $(".modal-dialog").width(1200);
        //});

        function wprem_gallery_thumbs() {
            setTimeout(function() {
                var galh = 10000000;
                $(".wprem-gal-container").css("height", "auto");
                $(".wprem-gal-container").each(function() {
                    if ($(this).height() < galh) {
                        galh = $(this).height();
                    }
                    console.log($(this).height());
                });
                $(".wprem-gal-container").height(galh);

            }, 100);
        }
        //wprem_gallery_thumbs();
        //$(window).resize(wprem_gallery_thumbs);

    });

})(jQuery);