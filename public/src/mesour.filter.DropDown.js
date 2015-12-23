/**
 * Mesour Filter Component - mesour.filter.DropDown.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
if (!mesour.filter) {
    throw new Error('Widget ' + mesour.filter + ' is not created. First create mesour.filter widget.');
}

mesour.filter.applyDropDown = function (filterName, href, filterData) {
    if (filterData !== '') {
        var name = filterData.id;
        var opened = filterData.opened;
        mesour.cookie(filterName + '-' + name, opened);
    }
};
mesour.filter.DropDown = function (element, name, filter) {
    var _this = this;

    var fixVariable = function (variable) {
        if (variable === null) {
            return mesour.filter.VALUE_NULL;
        } else if (variable === true) {
            return mesour.filter.VALUE_TRUE;
        } else if (variable === false) {
            return mesour.filter.VALUE_FALSE;
        }
        return variable;
    };
    this.fixVariable = function (variable) {
        return fixVariable(variable);
    };
    this.translateVariable = function (variable) {
        if (variable === null) {
            return '<i>' + mesour.filter.translations['empty'] + '</i>';
        } else if (variable === true) {
            return '<i>' + mesour.filter.translations['true'] + '</i>';
        } else if (variable === false) {
            return '<i>' + mesour.filter.translations['false'] + '</i>';
        } else if (translates[variable]) {
            return translates[variable];
        }
        return variable;
    };

    var type = element.attr('data-type');
    var translatesInput = element.find('[data-translates]');
    var translates = translatesInput.is('*') ? jQuery.parseJSON(translatesInput.val()) : [];

    var customFilter,
        checkers,
        mouseIn = false;

    var create = function () {
    };

    var destroy = function () {
        var ul = element.find('.box-inner').find('ul');
        ul.find('li:not(.all-select-li):not(.all-select-searched-li)').remove();
    };

    this.destroy = function () {
        destroy();
    };

    this.create = function (gridData, isAgain) {
        create(gridData, isAgain);
    };

    var apply = function (open) {
        mesour.filter.applyDropDown(filter.getName(), filter.getDropDownLink(), open);
    };

    create = function (gridData, isAgain) {
        gridData = !gridData ? filter.getData() : gridData;
        if (!gridData) return;
        var values = {};
        for (var x = 0; x < gridData.length; x++) {
            if (typeof gridData[x][name] === 'undefined') {
                throw new Error('MesourFilterDropDownException: Column "' + name + '" does not exists in data.');
            }
            if (!values[gridData[x][name]]) {
                values[fixVariable(gridData[x][name])] = {
                    val: fixVariable(gridData[x][name]),
                    translated: _this.translateVariable(gridData[x][name]),
                    keys: [x]
                };
            } else {
                values[gridData[x][name]].keys.push(x);
            }
        }

        if (!type) {
            var ul = element.find('.box-inner').find('ul');
            for (var y in values) {
                if (!values[y].val && Number(values[y].val) !== 0) continue;

                var li = $('<li>'),
                    id = name + ((values[y].val && typeof values[y].val.replace === 'function') ? values[y].val.replace(' ', '') : values[y].val);
                li.append('<input type="checkbox" class="checker" data-value="' + values[y].val + '" id="' + id + '">');
                li.append('&nbsp;');
                li.append('<label for="' + id + '">' + values[y].translated + '</label>');
                ul.append(li);
            }
        } else if (type === 'date') {
            var years = [],
                months = {},
                special = {};
            for (var y in values) {
                if (!values[y].val) continue;

                var isTimestamp = isNaN(values[y].val);

                if (values[y].val) {
                    if (values[y].val === mesour.filter.VALUE_NULL || values[y].val === mesour.filter.VALUE_TRUE || values[y].val === mesour.filter.VALUE_FALSE) {
                        special[values[y]] = values[y];
                    }
                }
                var timestamp = isTimestamp ? mesour.core.strtotime(values[y].val) : values[y].val;
                var year = mesour.core.phpDate('Y', timestamp);
                var month = mesour.core.phpDate('n', timestamp);
                var day = mesour.core.phpDate('j', timestamp);
                if (years.indexOf(year) === -1) {
                    years.push(year)
                }
                if (!months[year]) {
                    months[year] = {};
                    months[year]['months'] = [];
                    months[year]['days'] = {};
                }
                if (months[year]['months'].indexOf(month) === -1) {
                    months[year]['months'].push(month);
                }
                if (!months[year]['days'][month]) {
                    months[year]['days'][month] = [];
                }
                if (months[year]['days'][month].indexOf(day) === -1) {
                    months[year]['days'][month].push(day);
                }
            }
            years.sort(function (a, b) {
                return b - a
            });
            var ul = element.find('.box-inner').find('ul');
            for (var i in special) {
                if (!special.hasOwnProperty(i)) {
                    continue;
                }
                var li = $('<li>'),
                    id = name + ((special[i].val && typeof special[i].val.replace === 'function') ? special[i].val.replace(' ', '') : special[i].val);
                li.append('<input type="checkbox" class="checker" data-value="' + special[i].val + '" id="' + id + '">');
                li.append('&nbsp;');
                li.append('<label for="' + id + '">' + special[i].translated + '</label>');
                ul.append(li);
            }
            for (var a in years) {
                if (!years.hasOwnProperty(a)) {
                    continue;
                }
                var year_li = $('<li>');
                year_li.append('<span class="glyphicon glyphicon-plus toggle-sub-ul"></span>');
                year_li.append('&nbsp;');
                year_li.append('<input type="checkbox" class="checker">');
                year_li.append('&nbsp;');
                year_li.append('<label>' + years[a] + '</label>');
                year_li.append('<span class="close-all">(<a href="#">Close all</a>)</span>');
                var month_ul = $('<ul class="toggled-sub-ul">');
                year_li.append(month_ul);

                months[years[a]].months.sort(function (a, b) {
                    return a - b
                });
                var month = months[years[a]].months;
                for (var b in month) {
                    var month_li = $('<li>');
                    month_li.append('<span class="glyphicon glyphicon-plus toggle-sub-ul"></span>');
                    month_li.append('&nbsp;');
                    month_li.append('<input type="checkbox" class="checker">');
                    month_li.append('&nbsp;');
                    month_li.append('<label>' + mesour.filter.translations.months[month[b]] + '</label>');
                    month_ul.append(month_li);
                    var days_ul = $('<ul class="toggled-sub-ul">');
                    month_li.append(days_ul);

                    months[years[a]].days[month[b]].sort(function (a, b) {
                        return a - b
                    });
                    var days = months[years[a]].days[month[b]];
                    for (var c in days) {
                        var this_time = mesour.core.strtotime(years[a] + '-' + month[b] + '-' + days[c]);
                        var date_text = isTimestamp ? mesour.core.phpDate(filter.getPhpDateFormat(), this_time) : this_time;
                        var day_li = $('<li>');
                        day_li.append('<span class="glyphicon">&nbsp;</span>');
                        day_li.append('<input type="checkbox" class="checker" data-value="' + date_text + '">');
                        day_li.append('&nbsp;');
                        day_li.append('<label>' + days[c] + '</label>');
                        days_ul.append(day_li);
                    }
                }
                ul.append(year_li);
            }
        }
        if (isAgain) {
            //customFilter = new mesour.filter.CustomFilter(_this);
            checkers = new mesour.filter.Checkers(_this);
        }
    };

    create();

    this.getName = function () {
        return name;
    };

    this.getType = function () {
        return !type ? 'text' : type;
    };

    this.getElement = function () {
        return element;
    };

    this.getValues = function (valType) {
        var val = filter.getValues(name);
        if (!valType) {
            return val;
        } else {
            if (!val[valType]) {
                return {};
            } else {
                return val[valType];
            }
        }
    };

    this.setValues = function (newValues, valType) {
        var val = filter.getValues(name);
        val[valType] = newValues;
        filter.setValues(val, name);
    };

    this.unsetValues = function (valType) {
        var val = filter.getValues(name);
        delete val[valType];
        filter.setValues(val, name);
    };

    this.getFilter = function () {
        return filter;
    };

    customFilter = new mesour.filter.CustomFilter(this);
    checkers = new mesour.filter.Checkers(this);

    this.update = function () {
        var values = _this.getValues(),
            toggle_button = element.find('.dropdown-toggle'),
            menu = element.find('.dropdown-menu'),
            first_submenu = menu.children('.dropdown-submenu');
        toggle_button.find('.glyphicon-ok').hide();
        first_submenu.find('.glyphicon').closest('button').hide();
        element.removeClass('active-item').removeClass('active-checkers');

        if (values) {
            if (values.custom && values.custom.operator) {
                toggle_button.find('.glyphicon-ok').show();
                element.addClass('active-item');
                first_submenu.find('.glyphicon').closest('button').show();
            }
            if (values.checkers && typeof values.checkers[0] !== 'undefined') {
                toggle_button.find('.glyphicon-ok').show();
                element.addClass('active-checkers');
                for (var x = 0; x < values.checkers.length; x++) {
                    checkers.check(values.checkers[x]);
                }
            }
        }
    };

    this.toggle = function () {
        if (_this.isOpened()) {
            _this.close();
        } else {
            _this.open();
        }
    };

    this.isOpened = function () {
        return element.hasClass('open');
    };

    this.open = function () {
        filter.closeAll(element);
        element.addClass('open');
        apply({
            id: _this.getName(),
            opened: 1
        });
    };

    this.close = function () {
        _this.update();
        element.removeClass('open');
        apply({
            id: _this.getName(),
            opened: 0
        });
    };

    element.on({
        mouseenter: function () {
            mouseIn = true;
        },
        mouseleave: function () {
            mouseIn = false;
        }
    });

    $('.mesour-filter-modal').on({
        mouseenter: function () {
            mouseIn = true;
        },
        mouseleave: function () {
            mouseIn = false;
        }
    });

    $('html').on('click.filter-el-' + name, function () {
        if (_this.isOpened() && !mouseIn) {
            _this.close();
        }
    });

    element.children('button').on('click', function (e) {
        e.preventDefault();
        filter.closeAll(element);
        _this.toggle(element);
    });

    element.find('.reset-filter').on({
        click: function () {
            _this.unsetValues('custom');
            _this.update();
            _this.save();
            filter.apply();
        },
        mouseenter: function () {
            $(this).removeClass('btn-success').addClass('btn-danger');
        },
        mouseleave: function () {
            $(this).removeClass('btn-danger').addClass('btn-success');
        }
    });

    element.find('.close-filter').on('click', function (e) {
        e.preventDefault();
        _this.update();
        _this.close();
    });

    this.save = function () {
        var checked = checkers.getChecked();
        if (checked.length > 0) {
            _this.setValues(_this.getFilter().generateNextPriority(), 'priority');
            _this.setValues(checked, 'checkers');
            _this.setValues(type !== 'date' ? 'text' : 'date', 'type');
        } else {
            _this.unsetValues('priority');
            _this.unsetValues('checkers');
        }
        //_this.getFilter().filterCheckers();
        //_this.close();
    };

    this.update();
};