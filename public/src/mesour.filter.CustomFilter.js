/**
 * Mesour Filter Component - mesour.filter.CurstomFilter.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
if (!mesour.filter) {
    throw new Error('Widget ' + mesour.filter + ' is not created. First create mesour.filter widget.');
}

mesour.filter.CustomFilter = function (dropdown) {
    var $filter_modal = dropdown.getFilter().getFilterModal();
    var fixValue = function (value) {
        return value;
    };
    dropdown.getElement().find('.mesour-open-modal').on('click', function (e) {
        e.preventDefault();
        var $this = $(this),
            values = dropdown.getValues('custom'),
            type1 = $this.attr('data-type-first'),
            type2 = $this.attr('data-type-second'),
            firstValue = $this.attr('data-first-value'),
            secondValue = $this.attr('data-second-value'),
            operator = $this.attr('data-operator');

        if ($this.hasClass('edit-filter') && values) {
            type1 = values.how1;
            type2 = values.how2;
            operator = values.operator;
            firstValue = values.val1;
            secondValue = values.val2;
        }

        $filter_modal.find('[data-name]').val(dropdown.getName());

        if (firstValue) {
            var _val = fixValue(firstValue);
            if (typeof _val === 'string' && _val.split('-').length !== 3) {
                $filter_modal.find('.filter-value-1').val(_val);
                $filter_modal.find('.filter-value-1').removeAttr('data-date-defaultDate');
            } else {
                if (typeof _val === 'string' && _val.split('-').length === 3) {
                    _val = [_val];
                }
                $filter_modal.find('.filter-value-1').val(_val[0]);
                $filter_modal.find('.filter-value-1').attr('data-date-defaultDate', _val[0]);
            }
        } else {
            $filter_modal.find('.filter-value-1').val(null);
        }
        if (secondValue) {
            var _val = fixValue(secondValue);
            if (typeof _val === 'string') {
                $filter_modal.find('.filter-value-2').val(_val);
                $filter_modal.find('.filter-value-2').removeAttr('data-date-defaultDate');
            } else {
                $filter_modal.find('.filter-value-2').val(_val[0]);
                $filter_modal.find('.filter-value-2').attr('data-date-defaultDate', _val[0]);
            }
        } else {
            $filter_modal.find('.filter-value-2').val(null);
        }
        if (type1) {
            $filter_modal.find('.filter-how-1').val(type1);
        } else {
            $filter_modal.find('.filter-how-1').val(null);
        }
        if (type2) {
            $filter_modal.find('.filter-how-2').val(type2);
        } else {
            $filter_modal.find('.filter-how-2').val(null);
        }
        if (operator === 'or') {
            $filter_modal.find('input[name="operator"][value=or]').prop('checked', true);
        } else {
            $filter_modal.find('input[name="operator"][value=and]').prop('checked', true);
        }

        if (dropdown.getType() === 'date') {
            $filter_modal.find('.input-group-addon').show();
            mesour.dateTimePicker.destroy($filter_modal.find('.filter-datepicker1, .filter-datepicker2'));
            mesour.dateTimePicker.create($filter_modal.find('.filter-datepicker1, .filter-datepicker2'));
            $filter_modal.find('.filter-value-1, .filter-value-2').on('keydown.data-grid', function (e) {
                e.preventDefault();
                if (e.keyCode === 46 || e.keyCode === 8) {
                    $(this).val(null);
                }
            });
        } else {
            $filter_modal.find('.input-group-addon').hide();
            $filter_modal.find('.filter-value-1, .filter-value-2').off('keydown.data-grid');
        }

        $('.mesour-filter-modal').fadeIn(function () {
            $filter_modal.find('.filter-value-1').focus();
        });
    });
};