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
        array('user_id' => '1','action' => '0','group_id' => '1','name' => 'John','surname' => 'Doe','email' => 'john.doe@test.xx','last_login' => '2014-09-01 06:27:32','amount' => '1561.456542','avatar' => '/avatar/01.png','order' => '100','timestamp' => '1418255325'),
        array('user_id' => '2','action' => '1','group_id' => '2','name' => 'Peter','surname' => 'Larson','email' => 'peter.larson@test.xx','last_login' => '2014-09-09 13:37:32','amount' => '15220.654','avatar' => '/avatar/02.png','order' => '160','timestamp' => '1418255330'),
        array('user_id' => '3','action' => '1','group_id' => '2','name' => 'Claude','surname' => 'Graves','email' => 'claude.graves@test.xx','last_login' => '2014-09-02 14:17:32','amount' => '9876.465498','avatar' => '/avatar/03.png','order' => '180','timestamp' => '1418255311'),
        array('user_id' => '4','action' => '0','group_id' => '3','name' => 'Stuart','surname' => 'Norman','email' => 'stuart.norman@test.xx','last_login' => '2014-09-09 18:39:18','amount' => '98766.2131','avatar' => '/avatar/04.png','order' => '120','timestamp' => '1418255328'),
        array('user_id' => '5','action' => '1','group_id' => '1','name' => 'Kathy','surname' => 'Arnold','email' => 'kathy.arnold@test.xx','last_login' => '2014-09-07 10:24:07','amount' => '456.987','avatar' => '/avatar/05.png','order' => '140','timestamp' => '1418155313'),
        array('user_id' => '6','action' => '0','group_id' => '3','name' => 'Jan','surname' => 'Wilson','email' => 'jan.wilson@test.xx','last_login' => '2014-09-03 13:15:22','amount' => '123','avatar' => '/avatar/06.png','order' => '150','timestamp' => '1418255318'),
        array('user_id' => '7','action' => '0','group_id' => '1','name' => 'Alberta','surname' => 'Erickson','email' => 'alberta.erickson@test.xx','last_login' => '2014-08-06 13:37:17','amount' => '98753.654','avatar' => '/avatar/07.png','order' => '110','timestamp' => '1418255327'),
        array('user_id' => '8','action' => '1','group_id' => '3','name' => 'Ada','surname' => 'Wells','email' => 'ada.wells@test.xx','last_login' => '2014-08-12 11:25:16','amount' => '852.3654','avatar' => '/avatar/08.png','order' => '70','timestamp' => '1418255332'),
        array('user_id' => '9','action' => '0','group_id' => '2','name' => 'Ethel','surname' => 'Figueroa','email' => 'ethel.figueroa@test.xx','last_login' => '2014-09-05 10:23:26','amount' => '45695.986','avatar' => '/avatar/09.png','order' => '20','timestamp' => '1418255305'),
        array('user_id' => '10','action' => '1','group_id' => '3','name' => 'Ian','surname' => 'Goodwin','email' => 'ian.goodwin@test.xx','last_login' => '2014-09-04 12:26:19','amount' => '1236.9852','avatar' => '/avatar/10.png','order' => '130','timestamp' => '1418255331'),
        array('user_id' => '11','action' => '1','group_id' => '2','name' => 'Francis','surname' => 'Hayes','email' => 'francis.hayes@test.xx','last_login' => '2014-09-03 10:16:17','amount' => '5498.345','avatar' => '/avatar/11.png','order' => '0','timestamp' => '1418255293'),
        array('user_id' => '12','action' => '0','group_id' => '1','name' => 'Erma','surname' => 'Burns','email' => 'erma.burns@test.xx','last_login' => '2014-07-02 15:42:15','amount' => '63287.9852','avatar' => '/avatar/12.png','order' => '60','timestamp' => '1418255316'),
        array('user_id' => '13','action' => '1','group_id' => '3','name' => 'Kristina','surname' => 'Jenkins','email' => 'kristina.jenkins@test.xx','last_login' => '2014-08-20 14:39:43','amount' => '74523.96549','avatar' => '/avatar/13.png','order' => '40','timestamp' => '1418255334'),
        array('user_id' => '14','action' => '0','group_id' => '3','name' => 'Virgil','surname' => 'Hunt','email' => 'virgil.hunt@test.xx','last_login' => '2014-08-12 16:09:38','amount' => '65654.6549','avatar' => '/avatar/14.png','order' => '30','timestamp' => '1418255276'),
        array('user_id' => '15','action' => '1','group_id' => '1','name' => 'Max','surname' => 'Martin','email' => 'max.martin@test.xx','last_login' => '2014-09-01 12:14:20','amount' => '541236.5495','avatar' => '/avatar/15.png','order' => '170','timestamp' => '1418255317'),
        array('user_id' => '16','action' => '1','group_id' => '2','name' => 'Melody','surname' => 'Manning','email' => 'melody.manning@test.xx','last_login' => '2014-09-02 12:26:20','amount' => '9871.216','avatar' => '/avatar/16.png','order' => '50','timestamp' => '1418255281'),
        array('user_id' => '17','action' => '1','group_id' => '3','name' => 'Catherine','surname' => 'Todd','email' => 'catherine.todd@test.xx','last_login' => '2014-06-11 15:14:39','amount' => '100.2','avatar' => '/avatar/17.png','order' => '10','timestamp' => '1418255313'),
        array('user_id' => '18','action' => '0','group_id' => '1','name' => 'Douglas','surname' => 'Stanley','email' => 'douglas.stanley@test.xx','last_login' => '2014-04-16 15:22:18','amount' => '900','avatar' => '/avatar/18.png','order' => '90','timestamp' => '1418255332'),
        array('user_id' => '19','action' => '0','group_id' => '3','name' => 'Patti','surname' => 'Diaz','email' => 'patti.diaz@test.xx','last_login' => '2014-09-11 12:17:16','amount' => '1500','avatar' => '/avatar/19.png','order' => '80','timestamp' => '1418255275'),
        array('user_id' => '20','action' => '0','group_id' => '3','name' => 'John','surname' => 'Petterson','email' => 'john.petterson@test.xx','last_login' => '2014-10-10 10:10:10','amount' => '2500','avatar' => '/avatar/20.png','order' => '190','timestamp' => '1418255275')
    );

    $groups = array(
        array('id' => '2', 'name' => 'Group 2'),
        array('id' => '1', 'name' => 'Group 1'),
        array('id' => '3', 'name' => 'Group 3'),
    );

    $application = new \Mesour\UI\Application;

    $application->setRequest($_REQUEST);

    $filter = new \Mesour\UI\Filter('test');

    $application->addComponent($filter);

    $source = new \Mesour\Filter\Sources\ArrayFilterSource($data, array(
        'group' => $groups
    ));

    $source->setRelated('group', 'group_id', 'name', 'group_name');

    $filter->setSource($source);

    $filter->addTextFilter('name', 'Name');

    $filter->addNumberFilter('amount', 'Amount');

    $filter->addDateFilter('last_login', 'Last login');

    $filter->addTextFilter('group_name', 'Group name');

    $filter->onRender[] = function(\Mesour\UI\Filter $_filter) {
        dump($_filter->getValues());
    };

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
    .mesour-filter-dropdown .box-inner{
        margin-left: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-right: none;
        max-height: 200px;
        overflow: auto;
        overflow-x: hidden;
    }
    .mesour-filter-dropdown .box-inner ul{
        padding-left: 0;
        list-style-type: none;
        width: 305px;
    }
    .mesour-filter-dropdown .box-inner ul li{
        padding-left: 10px;
        line-height: 10px;
    }

    .mesour-filter-dropdown .box-inner li.all-select-li,
    .mesour-filter-dropdown .box-inner li.all-select-searched-li{
        border-bottom: 1px solid #ddd;
        padding-bottom: 3px;
    }
    .mesour-filter-dropdown .box-inner li.all-select-searched-li{
        display: none;
    }
    .mesour-filter-dropdown .box-inner li.li-checked > label{
        font-weight: bold;
    }
    .mesour-filter-dropdown .box-inner label{
        font-weight: normal;
        font-size: 12px;
    }
    .mesour-filter-dropdown .box-inner input{
        position: relative;
        top: 3px;
    }
    .mesour-filter-dropdown .search{
        margin-bottom: 10px;
        margin-left: 19px;
    }
    .mesour-filter-dropdown .search-input{
        top: 0;
        border-radius: 0;
        right: -1px;
    }
    .mesour-filter-dropdown .with-buttons{
        height: 33px;
        position: relative;
    }
    .mesour-filter-dropdown .buttons{
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