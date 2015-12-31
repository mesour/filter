<?php

define('SRC_DIR', __DIR__ . '/../src/');

require_once __DIR__ . '/../vendor/autoload.php';
define('DISABLE_AUTOLOAD', TRUE);
/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = require_once __DIR__ . '/../vendor/mesour/sources/demo/bootstrap.php';
require_once __DIR__ . '/../vendor/mesour/sources/tests/Entity/Groups.php';
require_once __DIR__ . '/../vendor/mesour/sources/tests/Entity/User.php';

@mkdir(__DIR__ . '/log');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');
\Tracy\Debugger::$strictMode = TRUE;

require_once SRC_DIR . 'Mesour/Filter/IFilter.php';
require_once SRC_DIR . 'Mesour/Filter/IFilterItem.php';
require_once SRC_DIR . 'Mesour/UI/Filter.php';
require_once SRC_DIR . 'Mesour/Filter/FilterItem.php';
require_once SRC_DIR . 'Mesour/Filter/Text.php';
require_once SRC_DIR . 'Mesour/Filter/Date.php';
require_once SRC_DIR . 'Mesour/Filter/Number.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/IFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/ArrayFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/DoctrineFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/NetteDBFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/SQLHelper.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/DateFunction.php';

?>

<hr>

<div class="container">
    <h2>Basic functionality</h2>

    <hr>

    <?php

    $data = [
        ['user_id' => '1', 'action' => '0', 'group_id' => '1', 'name' => NULL, 'surname' => 'Larson', 'email' => 'john.doe@test.xx', 'last_login' => '2014-09-01 06:27:32', 'amount' => '1561.456542', 'avatar' => '/avatar/01.png', 'order' => '100', 'timestamp' => '1418255325', 'bool' => TRUE],
        ['user_id' => '2', 'action' => '1', 'group_id' => '2', 'name' => 'John', 'surname' => 'Doe', 'email' => 'peter.larson@test.xx', 'last_login' => '2014-09-09 13:37:32', 'amount' => '15220.654', 'avatar' => '/avatar/02.png', 'order' => '160', 'timestamp' => '1418255330', 'bool' => FALSE],
        ['user_id' => '3', 'action' => '1', 'group_id' => '2', 'name' => 'Claude', 'surname' => 'Graves', 'email' => 'claude.graves@test.xx', 'last_login' => '2014-09-02 14:17:32', 'amount' => '9876.465498', 'avatar' => '/avatar/03.png', 'order' => '180', 'timestamp' => '1418255311', 'bool' => TRUE],
        ['user_id' => '4', 'action' => '0', 'group_id' => '3', 'name' => 'Stuart', 'surname' => 'Norman', 'email' => 'stuart.norman@test.xx', 'last_login' => '2014-09-09 18:39:18', 'amount' => '98766.2131', 'avatar' => '/avatar/04.png', 'order' => '120', 'timestamp' => '1418255328', 'bool' => TRUE],
        ['user_id' => '5', 'action' => '1', 'group_id' => '1', 'name' => 'Kathy', 'surname' => 'Arnold', 'email' => 'kathy.arnold@test.xx', 'last_login' => '2014-09-07 10:24:07', 'amount' => '456.987', 'avatar' => '/avatar/05.png', 'order' => '140', 'timestamp' => '1418155313', 'bool' => TRUE],
        ['user_id' => '6', 'action' => '0', 'group_id' => '3', 'name' => 'Jan', 'surname' => 'Wilson', 'email' => 'jan.wilson@test.xx', 'last_login' => '2014-09-03 13:15:22', 'amount' => '123', 'avatar' => '/avatar/06.png', 'order' => '150', 'timestamp' => '1418255318', 'bool' => TRUE],
        ['user_id' => '7', 'action' => '0', 'group_id' => '1', 'name' => 'Alberta', 'surname' => 'Erickson', 'email' => 'alberta.erickson@test.xx', 'last_login' => '2014-08-06 13:37:17', 'amount' => '98753.654', 'avatar' => '/avatar/07.png', 'order' => '110', 'timestamp' => '1418255327', 'bool' => TRUE],
        ['user_id' => '8', 'action' => '1', 'group_id' => '3', 'name' => 'Ada', 'surname' => 'Wells', 'email' => 'ada.wells@test.xx', 'last_login' => '2014-08-12 11:25:16', 'amount' => '852.3654', 'avatar' => '/avatar/08.png', 'order' => '70', 'timestamp' => '1418255332', 'bool' => FALSE],
        ['user_id' => '9', 'action' => '0', 'group_id' => '2', 'name' => 'Ethel', 'surname' => 'Figueroa', 'email' => 'ethel.figueroa@test.xx', 'last_login' => '2014-09-05 10:23:26', 'amount' => '45695.986', 'avatar' => '/avatar/09.png', 'order' => '20', 'timestamp' => '1418255305', 'bool' => FALSE],
        ['user_id' => '10', 'action' => '1', 'group_id' => '3', 'name' => 'Ian', 'surname' => 'Goodwin', 'email' => 'ian.goodwin@test.xx', 'last_login' => '2014-09-04 12:26:19', 'amount' => '1236.9852', 'avatar' => '/avatar/10.png', 'order' => '130', 'timestamp' => '1418255331', 'bool' => FALSE],
        ['user_id' => '11', 'action' => '1', 'group_id' => '2', 'name' => 'Francis', 'surname' => 'Hayes', 'email' => 'francis.hayes@test.xx', 'last_login' => '2014-09-03 10:16:17', 'amount' => '5498.345', 'avatar' => '/avatar/11.png', 'order' => '0', 'timestamp' => '1418255293', 'bool' => TRUE],
        ['user_id' => '12', 'action' => '0', 'group_id' => '1', 'name' => 'Erma', 'surname' => 'Burns', 'email' => 'erma.burns@test.xx', 'last_login' => '2014-07-02 15:42:15', 'amount' => '63287.9852', 'avatar' => '/avatar/12.png', 'order' => '60', 'timestamp' => '1418255316', 'bool' => TRUE],
        ['user_id' => '13', 'action' => '1', 'group_id' => '3', 'name' => 'Kristina', 'surname' => 'Jenkins', 'email' => 'kristina.jenkins@test.xx', 'last_login' => '2014-08-20 14:39:43', 'amount' => '74523.96549', 'avatar' => '/avatar/13.png', 'order' => '40', 'timestamp' => '1418255334', 'bool' => TRUE],
        ['user_id' => '14', 'action' => '0', 'group_id' => '3', 'name' => 'Virgil', 'surname' => 'Hunt', 'email' => 'virgil.hunt@test.xx', 'last_login' => '2014-08-12 16:09:38', 'amount' => '65654.6549', 'avatar' => '/avatar/14.png', 'order' => '30', 'timestamp' => '1418255276', 'bool' => FALSE],
        ['user_id' => '15', 'action' => '1', 'group_id' => '1', 'name' => 'Max', 'surname' => 'Martin', 'email' => 'max.martin@test.xx', 'last_login' => '2014-09-01 12:14:20', 'amount' => '541236.5495', 'avatar' => '/avatar/15.png', 'order' => '170', 'timestamp' => '1418255317', 'bool' => TRUE],
        ['user_id' => '16', 'action' => '1', 'group_id' => '2', 'name' => 'Melody', 'surname' => 'Manning', 'email' => 'melody.manning@test.xx', 'last_login' => '2014-09-02 12:26:20', 'amount' => '9871.216', 'avatar' => '/avatar/16.png', 'order' => '50', 'timestamp' => '1418255281', 'bool' => FALSE],
        ['user_id' => '17', 'action' => '1', 'group_id' => '3', 'name' => 'Catherine', 'surname' => 'Todd', 'email' => 'catherine.todd@test.xx', 'last_login' => '2014-06-11 15:14:39', 'amount' => '100.2', 'avatar' => '/avatar/17.png', 'order' => '10', 'timestamp' => '1418255313', 'bool' => TRUE],
        ['user_id' => '18', 'action' => '0', 'group_id' => '1', 'name' => 'Douglas', 'surname' => 'Stanley', 'email' => 'douglas.stanley@test.xx', 'last_login' => '2014-04-16 15:22:18', 'amount' => '900', 'avatar' => '/avatar/18.png', 'order' => '90', 'timestamp' => '1418255332', 'bool' => FALSE],
        ['user_id' => '19', 'action' => '0', 'group_id' => '3', 'name' => 'Patti', 'surname' => 'Diaz', 'email' => 'patti.diaz@test.xx', 'last_login' => '2014-09-11 12:17:16', 'amount' => '1500', 'avatar' => '/avatar/19.png', 'order' => '80', 'timestamp' => '1418255275', 'bool' => TRUE],
        ['user_id' => '20', 'action' => '0', 'group_id' => '3', 'name' => 'John', 'surname' => 'Petterson', 'email' => 'john.petterson@test.xx', 'last_login' => '2014-10-10 10:10:10', 'amount' => '2500', 'avatar' => '/avatar/20.png', 'order' => '190', 'timestamp' => '1418255275', 'bool' => FALSE]
    ];

    $groups = [
        ['id' => '2', 'name' => 'Group 2'],
        ['id' => '1', 'name' => 'Group 1'],
        ['id' => '3', 'name' => 'Group 3'],
    ];

    $application = new Mesour\UI\Application;

    $application->setRequest($_REQUEST);

    $filter = new Mesour\UI\Filter('test');

    $application->addComponent($filter);

    $qb = $entityManager->createQueryBuilder()
        ->select('u')
        ->from(Mesour\Sources\Tests\Entity\User::class, 'u');

    $source = new Mesour\Filter\Sources\DoctrineFilterSource($qb, [
        'user_id' => 'u.userId',
        'group_id' => 'u.groups',
        'last_login' => 'u.lastLogin',
        'group_name' => 'gr.name',
    ]);

    $entityManager->getConfiguration()->addCustomDatetimeFunction('DATE', Mesour\Filter\Sources\DateFunction::class);

    $source->setRelated(Mesour\Sources\Tests\Entity\Groups::class, 'group_id', 'name', 'group_name', 'id');

    $filter->setSource($source);

    $filter->addTextFilter('action', 'Status', [
        0 => 'Inactive',
        1 => 'Active',
    ])->setMainFilter(FALSE);

    $filter->addTextFilter('name', 'Name');

    $filter->addNumberFilter('amount', 'Amount');

    $filter->addDateFilter('lastLogin', 'Last login');

    $filter->addTextFilter('group_name', 'Group name');

    //$filter->addTextFilter('bool', 'Bool value');

    $filter->addDateFilter('timestamp', 'Last login');

    $filter->onRender[] = function (Mesour\UI\Filter $_filter) use ($source) {
        //dump($_filter->getValues());

        foreach ($_filter->getValues() as $name => $value) {
            if (isset($value['checkers'])) {
                $source->applyCheckers($name, $value['checkers'], $value['type']);
            }
            if (isset($value['custom'])) {
                $source->applyCustom($name, $value['custom'], $value['type']);
            }
        }
    };

    echo $filter->render();


    //dump($source->fetchAll());


    ?>
</div>

<hr>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="../public/datetimepicker.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<link rel="stylesheet" href="../public/src/mesour.filter.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../vendor/mesour/components/public/mesour.components.js"></script>
<script src="../vendor/mesour/components/public/cookie.js"></script>


<script src="../public/bootstrap.datetimepicker.js"></script>

<script src="../public/src/mesour.filter.js"></script>
<script src="../public/src/mesour.filter.Checkers.js"></script>
<script src="../public/src/mesour.filter.CustomFilter.js"></script>
<script src="../public/src/mesour.filter.Filter.js"></script>
<script src="../public/src/mesour.filter.DropDown.js"></script>