

(function ($) {
    "use strict";

    /* Collapse Navbar on Scroll
     ========================================================================== */
    var affix = $('#affix');
    if (affix.length > 0) {
        $(window).on('scroll', function () {
            var scroll = $(window).scrollTop();
            if (scroll >= 50) {
                affix.addClass("sticky");
            } else {
                affix.removeClass("sticky");
            }
        });
    }


    /* Testimonial Slider
     ========================================================================== */
    var testimonial_slider = $('#testimonial-slider');
    if (testimonial_slider.length > 0) {
        testimonial_slider.slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false,
            autoplay: false,
            mobileFirst:true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1
                    }
                },
            ]
        });
    }


    /* Client Slider
     ========================================================================== */
    var client_slider = $('#client-slider');
    if (client_slider.length > 0) {
        client_slider.slick({
            infinite: true,
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 2000,
            mobileFirst:true,
            appendArrows: '.button-container',
            responsive: [
                {
                    breakpoint: 499,
                    settings: {
                        slidesToShow:1
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 1023,
                    settings: {
                        slidesToShow:3
                    }
                },
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow:4
                    }
                }
            ]

        });
    }

    /* Wow JS
     ======================================*/
    var wow_selector = $('.wow');
    if (wow_selector.length > 0) {
        var wow = new WOW(
            {
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 0,
                mobile: false,
                scrollContainer: null // optional scroll container selector, otherwise use window
            }
        );
        wow.init();
    }

})(jQuery);