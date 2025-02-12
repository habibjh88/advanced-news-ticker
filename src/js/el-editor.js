/**
 * Elementor Editor Script
 */

/* global rtsbTemplateBuilderEditorScript, antSelect2Obj */

'use strict';


(function ($, elementor) {
    $(document).on('advanced_news_ticker_select2_event', function (event, obj) {
        AdvancedNewsTickerEditorHelpers.AdvancedNewsTickerSelect2Init(event, obj, $);
    });
})(jQuery, window.elementor);


const AdvancedNewsTickerEditorHelpers = {

    AdvancedNewsTickerSelect2Init: (event, obj, $) => {
        const advancedNewsTickerSelect = '#elementor-control-default-' + obj.data._cid;

        setTimeout(function () {
            const IDSelect2 = $(advancedNewsTickerSelect).select2({
                minimumInputLength: obj.data.minimum_input_length,
                maximumSelectionLength: obj.data.maximum_selection_length ?? -1,
                allowClear: true,
                ajax: {
                    url: antSelect2Obj.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    method: 'POST',
                    data(params) {
                        return {
                            action: 'advanced_news_ticker_select2_search',
                            _ajax_nonce: antSelect2Obj.nonce,
                            post_type: obj.data.source_type,
                            source_name: obj.data.source_name,
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                },
                initSelection(element, callback) {
                    if (!obj.multiple) {
                        callback({id: '', text: antSelect2Obj.search_text});
                    } else {
                        callback({id: 9999, text: 'search'});
                    }
                    let ids = [];
                    if (!Array.isArray(obj.currentID) && obj.currentID != '') {
                        ids = [obj.currentID];
                    } else if (Array.isArray(obj.currentID)) {
                        ids = obj.currentID.filter(function (el) {
                            return el != null;
                        });
                    }

                    if (ids.length > 0) {
                        const label = $(
                            "label[for='elementor-control-default-" +
                            obj.data._cid +
                            "']"
                        );
                        label.after(
                            '<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>'
                        );
                        $.ajax({
                            url: antSelect2Obj.ajaxurl, // + '?action=advanced_news_ticker_select2_get_title',
                            method: 'POST',
                            data: {
                                action:'advanced_news_ticker_select2_get_title',
                                post_type: obj.data.source_type,
                                source_name: obj.data.source_name,
                                _ajax_nonce: antSelect2Obj.nonce,
                                id: ids,
                            },
                        }).done(function (response) {
                            if (
                                response.success &&
                                typeof response.data.results !== 'undefined'
                            ) {
                                let advancedNewsTickerSelect2Options = '';
                                ids.forEach(function (item, index) {
                                    if (
                                        typeof response.data.results[item] !==
                                        'undefined'
                                    ) {
                                        const key = item;
                                        const value =
                                            response.data.results[item];
                                        advancedNewsTickerSelect2Options += `<option selected="selected" value="${key}">${value}</option>`;
                                    }
                                });

                                element.append(advancedNewsTickerSelect2Options);
                            }
                            label
                                .siblings('.elementor-control-spinner')
                                .remove();
                        });
                    }
                },
            });

            //Select2 drag and drop : starts
            setTimeout(function () {
                IDSelect2.next()
                    .children()
                    .children()
                    .children()
                    .sortable({
                        containment: 'parent',
                        stop(event, ui) {
                            ui.item
                                .parent()
                                .children('[title]')
                                .each(function () {
                                    const title = $(this).attr('title');
                                    const original = $(
                                        'option:contains(' + title + ')',
                                        IDSelect2
                                    ).first();
                                    original.detach();
                                    IDSelect2.append(original);
                                });
                            IDSelect2.change();
                        },
                    });
            }, 200);
        }, 100);
    },
};
