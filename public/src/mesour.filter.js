/**
 * Mesour Selection Component - selection.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
mesour.filter = !mesour.filter ? {} : mesour.filter;

(function ($) {
    var Filter = function () {
        var _this = this;

        this.VALUE_TRUE = '-mesour-bool-1';
        this.VALUE_FALSE = '-mesour-bool-0';
        this.VALUE_NULL = '-mesour-null';

        this.ICON_ITEM_ACTIVE = 'itemIsActive';
        this.ICON_EDIT_CUSTOM = 'editCustom';
        this.ICON_REMOVE_CUSTOM = 'removeCustom';
        this.ICON_PLUS = 'plus';
        this.ICON_MINUS = 'minus';
        this.ICON_CALENDAR = 'calendar';

        this.PREDEFINED_KEY = 'predefined';

        this.translations = {
            months: {
                1: 'January',
                2: 'February',
                3: 'March',
                4: 'April',
                5: 'May',
                6: 'June',
                7: 'July',
                8: 'August',
                9: 'September',
                10: 'October',
                11: 'November',
                12: 'December'
            },
            closeAll: 'close all',
            'empty': 'empty',
            'true': 'true',
            'false': 'false'
        };

        this.filters = {};

        this.create = function () {
            $('[data-mesour-filter]').each(function () {
                var $this = $(this),
                    name = $this.attr('data-mesour-filter');

                var filter = $this.data('mesour-filter-instance');
                if (!filter) {
                    _this.filters[name] = filter = new mesour.filter.Filter(name, $this);
                    $this.data('mesour-filter-instance', filter);
                }
                $.each(filter.getDropdowns(), function (key, dropdown) {
                    dropdown.destroy();
                    dropdown.create();
                    dropdown.update();
                    dropdown.getFilter().filterCheckers();
                    if (mesour.cookie(name + '-' + dropdown.getName()) === '1') {
                        dropdown.open();
                    }
                });
            });
        };
    };
    mesour.core.createWidget('filter', new Filter());

    mesour.on.live('mesour-filter', function () {
        mesour.filter.create();
    });
})(jQuery);