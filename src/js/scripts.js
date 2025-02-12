;(function ($) {

    'use strict'

    $('a[href=\\#]').on('click', function (e) {
        e.preventDefault()
    })

    var AdvancedNewsTicker = {

        _init: function () {
            AdvancedNewsTicker.newsTicker()
        },

        newsTicker: function () {
            $('.advanced-news-ticker.swiper').each(function () {
                var swiperElement = $(this)
                // swiperElement.css({ 'opacity': 1, 'transition': 'opacity 0.4s ease-in-out' })
                var pauseBtn = swiperElement.find('.swiper-pause')
                var tickerContent = swiperElement.find('.ticker-content')
                var swiperConfig = swiperElement.data('swiper')
                var TickerSwiper;

                if ('undefined' === typeof Swiper) {
                    var asyncSwiper = elementorFrontend.utils.swiper
                    new asyncSwiper(swiperElement[0], swiperConfig).then((newSwiperInstance) => {
                        TickerSwiper = newSwiperInstance
                    })
                } else {
                    TickerSwiper = new Swiper(swiperElement[0], swiperConfig)
                }

                pauseBtn.on('click', function (e) {
                    e.preventDefault()
                    $(this).toggleClass('pause-enable')
                    const isPaused = swiperElement.toggleClass('ticker-pause-enable').hasClass('ticker-pause-enable')
                    TickerSwiper.autoplay[isPaused ? 'stop' : 'start']()
                })

                if (swiperConfig.pauseOnMouseEnter) {
                    tickerContent.on('mouseenter', function () {
                        TickerSwiper.autoplay.stop()
                    })

                    tickerContent.on('mouseleave', function () {
                        if (!swiperElement.hasClass('ticker-pause-enable')) {
                            TickerSwiper.autoplay.start()
                        }
                    })
                }
            })
        },
    }

    $(document).ready(function (e) {
        AdvancedNewsTicker._init()
    })

    $(window).on('elementor/frontend/init', () => {

        if (elementorFrontend.isEditMode()) {
            //For all widgets
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', () => {
                AdvancedNewsTicker._init()
            })
        }

    })
    window.AdvancedNewsTicker = AdvancedNewsTicker

})(jQuery)
