/**
 * Mesour Filter Component - mesour.filter.Checkers.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
if(!mesour.filter) {
    throw new Error('Widget '+mesour.filter+' is not created. First create mesour.filter widget.');
}

mesour.filter.Checkers = function (dropdown) {
    var allCheckedCheckbox = dropdown.getElement().find('.select-all'),
        allSearchedCheckedCheckbox = dropdown.getElement().find('.select-all-searched'),
        checkers = dropdown.getElement().find('.box-inner ul .checker'),
        searchInput = dropdown.getElement().find('.search-input').val(null),
        checkChecked = function (all_checkers, master_checker) {
            var allChecked = true;
            all_checkers.each(function () {
                if (!$(this).is(':checked')) {
                    allChecked = false;
                }
            });
            if (allChecked) {
                master_checker.prop('checked', true)
                    .closest('li').addClass('li-checked');
            } else {
                master_checker.prop('checked', false)
                    .closest('li').removeClass('li-checked');
            }
        },
        checkAllChecked = function (triggered) {
            if (allSearchedCheckedCheckbox.is(':visible')) {
                checkChecked(checkers.filter(':visible'), allSearchedCheckedCheckbox);
            }
            checkChecked(checkers, allCheckedCheckbox);
            if(!triggered) {
                dropdown.save();
                dropdown.getFilter().apply();
            }
        },
        allCheckboxCallback = function (e) {
            var $this = $(this);
            var visible = !$this.hasClass('select-all-searched') ? '' : ':visible';
            if ($this.is(':checked')) {
                $this.closest('li').addClass('li-checked')
                    .closest('ul').find('.checker' + visible).prop('checked', true)
                    .trigger('change', true);
            } else {
                $this.closest('li').removeClass('li-checked')
                    .closest('ul').find('.checker' + visible).prop('checked', false)
                    .trigger('change', true);
            }
            dropdown.save();
            dropdown.getFilter().apply();
        },
        checkAllSubChecked = function ($checker) {
            var sub_ul = $checker.closest('.toggled-sub-ul');
            if (!sub_ul.is('*')) return;
            checkChecked(sub_ul.children('li').children('.checker'), sub_ul.closest('li').children('.checker'));
            var sub_sub_ul = sub_ul.closest('li').parent('ul').closest('li');
            if (!sub_sub_ul.is('*')) return;
            checkChecked(sub_sub_ul.children('ul').children('li').children('.checker'), sub_sub_ul.children('.checker'));
        };

    dropdown.getElement().find('.all-select-searched-li').hide();

    allCheckedCheckbox.off('change.data-grid');
    allCheckedCheckbox.on('change.data-grid', allCheckboxCallback);

    allSearchedCheckedCheckbox.off('change.data-grid');
    allSearchedCheckedCheckbox.on('change.data-grid', allCheckboxCallback);
    checkers.on('change', function (e, triggered) {
        var $this = $(this),
            li = $this.closest('li'),
            sub_ul = li.find('.toggled-sub-ul');

        if ($this.is(':checked')) {
            li.addClass('li-checked');
            if (sub_ul.is('*')) {
                sub_ul.find('.checker').prop('checked', true)
                    .closest('li').addClass('li-checked');
            }
        } else {
            li.removeClass('li-checked');
            if (sub_ul.is('*')) {
                sub_ul.find('.checker').prop('checked', false)
                    .closest('li').removeClass('li-checked');
            }
        }
        checkAllSubChecked($this);
        checkAllChecked(triggered);
    });
    checkers.next('label').each(function () {
        var $this = $(this);
        if ($this.text().length > 40) {
            $this.text($this.text().substr(0, 37) + '...');
        }
    });
    dropdown.getElement().find('.close-all a').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.closest('li').children('ul').find('ul').each(function() {
            var sub = $(this);
            sub.slideUp();
            sub.closest('li').find('.toggle-sub-ul').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        });
    });
    dropdown.getElement().find('.toggle-sub-ul').on('click', function(e) {
        e.preventDefault();
        var $this = $(this),
            subselect = $this.closest('li').children('ul'),
            closeAll = $this.closest('li').children('.close-all');
        if(subselect.is(':visible')) {
            subselect.slideUp();
            closeAll.hide();
            $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
        } else {
            subselect.slideDown();
            closeAll.show();
            $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }
    });
    searchInput.off('keyup.filter-checkers');
    searchInput.on('keyup.filter-checkers', function () {
        var $this = $(this),
            value = mesour.core.removeDiacritics($this.val().toLowerCase()),
            checkers = $this.closest('.inline-box').next('.box-inner').find('ul .checker'),
            one_hide = false;

        allSearchedCheckedCheckbox.closest('li').hide();
        checkers.closest('li').show();
        checkers.closest('li').each(function () {
            var $li = $(this);
            if (mesour.core.removeDiacritics($li.text().toLowerCase()).indexOf(value) === -1) {
                $li.hide();
                one_hide = true;
            }
        });
        if (one_hide) {
            allSearchedCheckedCheckbox.closest('li').show();
        }
        checkAllChecked(true);
    });

    this.getChecked = function() {
        var values = [];
        checkers.filter('[data-value]').each(function(){
            var $this = $(this);
            if($this.is(':checked')) {
                values.push($this.attr('data-value'))
            }
        });
        return values;
    };

    this.check = function(val) {
        checkers.filter('[data-value="'+dropdown.fixVariable(val)+'"]').prop('checked', true)
            .trigger('change', true);
    };
};