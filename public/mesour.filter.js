/**
 * Mesour Selection Component - selection.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
mesour.filter = !mesour.filter ? {} : mesour.filter;

(function($) {
    var Filter = function() {
        var _this = this;

        this.filters = {};

        this.create = function() {
            $('[data-mesour-data]').each(function () {
                var $this = $(this),
                    name = $this.attr('data-mesour-filter');

                var filter = $this.data('mesour-filter-instance');
                if(!filter) {
                    _this.filters[name] = filter = new mesour.filter.Filter(name, $this);
                    $this.data('mesour-filter-instance', filter);
                }
                $.each(filter.getDropdowns(), function(key,dropdown) {
                    dropdown.destroy();
                    dropdown.create();
                    dropdown.update();
                    dropdown.getFilter().filterCheckers();
                    if(mesour.cookie(name+'-'+dropdown.getName()) === '1') {
                        dropdown.open();
                    }
                });
            });
        };
    };
    mesour.core.createWidget('filter', new Filter());

    mesour.on.live('mesour-filter', function() {
        mesour.filter.create();
    });
})(jQuery);