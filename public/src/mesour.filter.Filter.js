/**
 * Mesour Filter Component - mesour.filter.Filter.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
if(!mesour.filter) {
    throw new Error('Widget mesour.filter is not created. First create mesour.filter widget.');
}

mesour.filter.applyFilter = function(filterName, href, filterData) {

};
mesour.filter.Filter = function (filterName, element) {
    var _this = this;

    var dropdowns = {};
    var valuesInput = element;

    var modal = $('<div class="mesour-filter-modal modal-dialog"> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> <h4 class="modal-title">Custom filter</h4> </div> <div class="modal-body"> <form class="form-inline"> <p>Show rows where:</p> <div class="form-group"> <label class="sr-only" for="grid-how-1"></label> <select id="grid-how-1" class="form-control"> <option></option> <option value="equal_to">Equal to</option> <option value="not_equal_to">Not equal to</option> <option value="bigger">Is greater than</option> <option value="not_bigger">Is no greater than</option> <option value="smaller">Is smaller than</option> <option value="not_smaller">Is no smaller than</option> <option value="start_with">Starts with</option> <option value="not_start_with">Not starts with</option> <option value="end_with">Ends with</option> <option value="not_end_with">Not ends with</option> <option value="equal">Contains</option> <option value="not_equal">Not contains</option> </select> </div> <div class="form-group"> <label class="sr-only" for="grid-value-1">Value</label> <div class="input-group date" id="grid-datepicker1"> <input type="text" class="form-control" data-date-format="{$js_date}" id="grid-value-1" placeholder="Value"> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div> </div> <br> <div class="form-group grid-operators"> <input type="radio" name="operator" id="grid-operator-and" value="and" checked="checked"> <label for="grid-operator-and">and</label> <input type="radio" name="operator" id="grid-operator-or" value="or"> <label for="grid-operator-or">or</label> </div> <br> <div class="form-group"> <label class="sr-only" for="grid-how-2"></label> <select id="grid-how-2" class="form-control"> <option></option> <option value="equal_to">Equal to</option> <option value="not_equal_to">Not equal to</option> <option value="bigger">Is greater than</option> <option value="not_bigger">Is no greater than</option> <option value="smaller">Is smaller than</option> <option value="not_smaller">Is no smaller than</option> <option value="start_with">Starts with</option> <option value="not_start_with">Not starts with</option> <option value="end_with">Ends with</option> <option value="not_end_with">Not ends with</option> <option value="equal">Contains</option> <option value="not_equal">Not contains</option> </select> </div> <div class="form-group"> <label class="sr-only" for="grid-value-2">Value</label> <div class="input-group date" id="grid-datepicker2"> <input type="text" class="form-control" data-date-format="{$js_date}" id="grid-value-2" placeholder="Value"> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div> </div> </form> </div> <div class="modal-footer"> <button type="button" class="btn btn-primary btn-sm save-custom-filter">Ok</button> <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Storno</button> </div> <input type="hidden" data-name=""> </div><!-- /.modal-content --> </div><!-- /.modal-dialog -->');;    var applyButton = $('.apply-filter[data-filter-name="'+filterName+'"]');
    $('body').append(modal);
    var dropDownLink = element.attr('data-dropdown-link');
    var resetButton = $('.full-reset[data-filter-name="'+filterName+'"]');

    this.apply = function() {
        mesour.filter.applyFilter(filterName, applyButton.attr("data-href"), valuesInput.val());
    };

    this.getDropdowns = function() {
        return dropdowns;
    };

    this.getName = function () {
        return filterName;
    };

    this.getDropDownLink = function() {
        return dropDownLink;
    };

    this.getFilterModal = function() {
        return modal;
    };

    this.closeAll = function(notThis) {
        for(var x in dropdowns) {
            dropdowns[x].update();
        }
        element.find('.dropdown').each(function() {
            if(!notThis || $(this)[0] !== notThis[0]) {
                $(this).removeClass('open');
                mesour.cookie(filterName+'-'+$(this).attr('data-filter'), 0);
            }
        });
    };

    var data = $.parseJSON(valuesInput.attr('data-mesour-data'));

    this.getData = function () {
        return data;
    };

    this.getPhpDateFormat = function () {
        return mesour.dataGrid.list[filterName].phpFilterDate;
    };

    this.getValues = function (name) {
        var val = valuesInput.val();
        val = val.length === 0 ? {} : $.parseJSON(val);
        if (!name) {
            return val;
        } else {
            if (!val[name]) {
                return {};
            } else {
                return val[name];
            }
        }
    };

    this.setValues = function(newValues, name) {
        var oldValues = valuesInput.val().length > 0 ? $.parseJSON(valuesInput.val()) : {};
        oldValues[name] = newValues;
        valuesInput.val(JSON.stringify(oldValues));
    };

    this.refreshPriorities = function() {
        var _currentValues = _this.getValues();
        var _usedPriorities = {};
        for(var x in _currentValues) {
            _usedPriorities[_currentValues[x].priority] = x;
        }
        var keys = [];

        for (var k in _usedPriorities) {
            if (_usedPriorities.hasOwnProperty(k)) {
                keys.push(k);
            }
        }
        keys.sort();
        var priority = 1;
        for (var i = 0; i < keys.length; i++) {
            k = keys[i];
            if(_currentValues[_usedPriorities[k]].priority) {
                _currentValues[_usedPriorities[k]].priority = priority;
                priority++
            }
        }
        valuesInput.val(JSON.stringify(_currentValues));
    };

    this.generateNextPriority = function() {
        _this.refreshPriorities();
        var currentValues = _this.getValues();
        var usedPriorities = [];
        for(var x in currentValues) {
            usedPriorities.push(currentValues[x].priority);
        }
        if(usedPriorities.length > 0) {
            var nextPriority = 1;
            for(var y = 0; y < usedPriorities.length;y++) {
                if(usedPriorities[y] > nextPriority) {
                    nextPriority = usedPriorities[y]+1;
                } else if(usedPriorities[y] === nextPriority) {
                    nextPriority++;
                }
            }
            return nextPriority;
        } else {
            return 1;
        }
    };

    this.filterData = function(key, valuesArr) {
        var data = _this.getData(),
            output = [];
        for(var x in data) {
            if(valuesArr.indexOf(data[x][key]) !== -1) {
                output.push(data[x]);
            }
        }
        return output;
    };

    this.filterCheckers = function() {
        var currentValues = _this.getValues(),
            usedPriorities = {};

        for(var x in currentValues) {
            usedPriorities[currentValues[x].priority] = x;
        }
        var keys = [];
        for (var k in usedPriorities) {
            if (usedPriorities.hasOwnProperty(k)) {
                keys.push(k);
            }
        }
        keys.sort();
        var usedDropdowns = {},
            newData = _this.getData();
        for (var i = 0; i < keys.length; i++) {
            k = keys[i];
            usedDropdowns[usedPriorities[k]] = true;
            dropdowns[usedPriorities[k]].destroy();
            dropdowns[usedPriorities[k]].create(newData, true);
            dropdowns[usedPriorities[k]].update();
            if(currentValues[usedPriorities[k]].checkers && currentValues[usedPriorities[k]].checkers.length > 0)
                newData = _this.filterData(usedPriorities[k], currentValues[usedPriorities[k]].checkers);
        }
        for(var x in dropdowns) {
            var dropdown = dropdowns[x];
            if(usedDropdowns[dropdown.getName()]) continue;
            dropdowns[x].destroy();
            dropdowns[x].create(newData, true);
            dropdowns[x].update();
        }
    };

    resetButton.on('click', function(e) {
        e.preventDefault();
        $.each(_this.getDropdowns(), function(key, dropdown) {
            dropdown.unsetValues('custom');
            dropdown.unsetValues('priority');
            dropdown.unsetValues('checkers');
            dropdown.update();
            dropdown.getFilter().filterCheckers();
        });
        _this.apply();
    });

    $('.dropdown[data-filter-name="'+filterName+'"]').each(function () {
        var $this = $(this),
            name = $this.attr('data-filter');
        dropdowns[name] = new mesour.filter.DropDown($this, name, _this);
        $this.data('grid-filter-dropdown', dropdowns[name]);
    });

    _this.filterCheckers();

    modal.find('[aria-hidden="true"], [data-dismiss="modal"]').on('click.custom-filter', function(e) {
        e.preventDefault();
        $(this).closest('.modal-dialog').fadeOut();
    });

    if($.fn.bootstrapDatetimepicker) {
        $('#grid-datepicker1, #grid-datepicker2').bootstrapDatetimepicker({
            pickTime: false
        });
    }

    modal.find('.save-custom-filter').on('click', function() {
        var name = modal.find('[data-name]').val(),
            internalValues = {
                how1: modal.find('#grid-how-1').val(),
                how2: modal.find('#grid-how-2').val(),
                val1: modal.find('#grid-value-1').val(),
                val2: modal.find('#grid-value-2').val(),
                operator: modal.find('input[name="operator"]:checked').val()
            };
        if(internalValues.how1.length === 0) {
            alert('Please select some value in first select.');
            modal.find('#grid-how-1').focus();return;
        }
        if(internalValues.val1.length === 0) {
            alert('Please insert some value for first text input.');
            modal.find('#grid-value-1').focus();return;
        }
        if(internalValues.how2.length !== 0 && internalValues.val2.length === 0) {
            alert('Please insert some value for second input.');
            modal.find('#grid-value-2').focus();return;
        }
        dropdowns[name].setValues(internalValues, 'custom');
        dropdowns[name].setValues(dropdowns[name].getType() !== 'date' ? 'text' : 'date', 'type');
        $(this).closest('.modal-dialog').fadeOut();
        dropdowns[name].update();
        dropdowns[name].save();
        dropdowns[name].getFilter().apply();
    });
};