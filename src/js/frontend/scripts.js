;(function ($) {

    'use strict';

    $('a[href=\\#]').on('click', function (e) {
        e.preventDefault();
    })

    var AdvancedNewsTicker = {

        _init: function () {
            AdvancedNewsTicker.newsTicker();
        },

        newsTicker: function () {
            $('.ant-newsticker').each(function (e) {
                var element = $(this);
                element.css('opacity','1')
                var data = element.data('newsticker');
                if ($.fn.breakingNews) {
                    element.breakingNews(data);
                }
            });
        },
    };

    $(document).ready(function (e) {
        AdvancedNewsTicker._init();
    });


    $(window).on('elementor/frontend/init', () => {

        if (elementorFrontend.isEditMode()) {
            //For all widgets
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', () => {
                AdvancedNewsTicker._init();
            });
        }

    });
    window.AdvancedNewsTicker = AdvancedNewsTicker;

})(jQuery);
