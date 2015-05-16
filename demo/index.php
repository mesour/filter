<?php

define('SRC_DIR', __DIR__ . '/../src/');

require_once __DIR__ . '/../vendor/autoload.php';

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');

require_once SRC_DIR . 'IFilter.php';
require_once SRC_DIR . 'IFilterItem.php';
require_once SRC_DIR . 'Filter.php';
require_once SRC_DIR . 'Filter/FilterItem.php';
require_once SRC_DIR . 'Filter/Text.php';
require_once SRC_DIR . 'Filter/Date.php';
require_once SRC_DIR . 'Filter/Number.php';


\Mesour\UI\Control::$default_link = new \Mesour\Components\Link\Link();

?>

<hr>

<div class="container">
    <h2>Basic functionality</h2>

    <hr>

    <?php

    $data = array(
        array(
            'method' => 'setName',
            'params' => '$name',
            'returns' => 'Mesour\Table\Column',
            'description' => 'Set column name.',
        ),
        array(
            'method' => 'setHeader',
            'params' => '$header',
            'returns' => 'Mesour\Table\Column',
            'description' => 'Set header text.',
        ),
        array(
            'method' => 'setCallback',
            'params' => '$callback',
            'returns' => 'Mesour\Table\Column',
            'description' => 'Set render callback.',
        )
    );



    $filter = new \Mesour\UI\Filter('test');

    $filter->addFilterItem('name', new \Mesour\Filter\Text());

    $filter->addFilterItem('amount', new \Mesour\Filter\Number());

    $filter->addFilterItem('datetime', new \Mesour\Filter\Date());

    $filter->render();

    ?>
</div>

<hr>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../docs/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../docs/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../docs/js/jquery.min.js"></script>
<script src="../docs/js/bootstrap.min.js"></script>
<script src="../vendor/mesour/components/public/mesour.components.js"></script>
<script src="../vendor/mesour/components/public/cookie.js"></script>
<script src="../public/mesour.filter.js"></script>
<script src="../public/src/mesour.filter.Checkers.js"></script>
<script src="../public/src/mesour.filter.CustomFilter.js"></script>
<script src="../public/src/mesour.filter.Filter.js"></script>
<script src="../public/src/mesour.filter.DropDown.js"></script>
<script src="../docs/js/main.js"></script>

<style>
    .dropdown.mesour-filter-dropdown{
        position: relative;
        display: inline-block;
        margin-right: 5px;
    }
    .dropdown.mesour-filter-dropdown .dropdown-menu > .dropdown-submenu > span >button{
        display: none;
    }
    .data-grid-filter .inline-box .box-inner{
        margin-left: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-right: none;
        max-height: 200px;
        overflow: auto;
        overflow-x: hidden;
    }
    .data-grid-filter .inline-box ul{
        padding-left: 0;
        list-style-type: none;
        width: 305px;
    }
    .data-grid-filter .inline-box ul li{
        padding-left: 10px;
        line-height: 10px;
    }

    .data-grid-filter .inline-box li.all-select-li,
    .data-grid-filter .inline-box li.all-select-searched-li{
        border-bottom: 1px solid #ddd;
        padding-bottom: 3px;
    }
    .data-grid-filter .inline-box li.all-select-searched-li{
        display: none;
    }
    .data-grid-filter .inline-box li.li-checked > label{
        font-weight: bold;
    }
    .data-grid-filter .inline-box label{
        font-weight: normal;
        font-size: 12px;
    }
    .data-grid-filter .inline-box input{
        position: relative;
        top: 3px;
    }
    .data-grid-filter .search{
        margin-bottom: 10px;
        margin-left: 19px;
    }
    .data-grid-filter .search-input{
        top: 0;
        border-radius: 0;
        right: -1px;
    }
    .data-grid-filter .with-buttons{
        height: 33px;
        position: relative;
    }
    .data-grid-filter .buttons{
        position: absolute;
        right: 10px;
    }
    .mesour-filter-dropdown .reset-filter.btn-danger .glyphicon-ok{
        display: none;
    }
    .mesour-filter-dropdown .reset-filter.btn-success .glyphicon-remove{
        display: none;
    }
    .mesour-filter-dropdown .dropdown-toggle .glyphicon-ok{
        display: none;
    }
    .dropdown.mesour-filter-dropdown .dropdown-menu>li>span {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: 400;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
    }
    .dropdown.mesour-filter-dropdown .dropdown-menu>li>span a {
        color: #000;
    }
    .dropdown.mesour-filter-dropdown .dropdown-menu>li>span a:hover {
        text-decoration: none;
    }
    .dropdown.mesour-filter-dropdown .dropdown-menu>li>span:hover,
    .dropdown.mesour-filter-dropdown .dropdown-menu>li>span:focus {
        color: #262626;
        text-decoration: none;
        background-color: #f5f5f5;
    }
    .mesour-filter-modal{
        position: fixed;
        top: 150px;
        left: 28%;
    }
    .mesour-filter-modal.modal-dialog{
        position: fixed;
        left: 36%;
        z-index: 1500;
        display: none;
        width: 450px;
        top: 100px;
    }
    .form-inline .form-group.grid-operators{
        margin-left: 38px;
        margin-top: 5px;
        margin-bottom: 3px;
    }
    .form-inline .form-group.grid-operators label{
        cursor: pointer;
    }
    .grid-operators #grid-operator-and,
    .grid-operators #grid-operator-or{
        position: relative;
        top: 3px;
    }
    .close-all{
        display: none;
    }
    .close-all a{
        font-size: 11px;
    }
    .toggle-sub-ul{
        font-size: 10px;
        border: 1px solid #ccc;
        padding: 2px 2px 3px 4px;
        cursor: pointer;
    }
    .toggle-sub-ul:hover{
        border-color: #aaa;
    }
    .toggled-sub-ul{
        display: none;
    }

    /* bootstrap dropdown submenu */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        -webkit-border-radius: 0 6px 6px 6px;
        -moz-border-radius: 0 6px 6px;
        border-radius: 0 6px 6px 6px;
    }

    .dropdown-submenu:hover>.dropdown-menu {
        display: block;
    }
    .dropdown-submenu>.dropdown-menu .dropdown-menu {
        margin-top: -7px;
    }

    .dropdown-submenu>a:after,
    .dropdown-submenu>span:after{
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }

    .dropdown-submenu:hover>a:after,
    .dropdown-submenu:hover>span:after{
        border-left-color: #000;
    }

    .dropdown-submenu.pull-left {
        float: none;
    }
    .dropdown-submenu.pull-left>.dropdown-menu {
        left: -100%;
        margin-left: 10px;
        -webkit-border-radius: 6px 0 6px 6px;
        -moz-border-radius: 6px 0 6px 6px;
        border-radius: 6px 0 6px 6px;
    }
</style>