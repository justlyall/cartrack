<?php

use Cartrack\Db\Database;

/** var $config  */
include '/var/www/html/config.php';
include 'DatabaseTestCase.php';

Database::connect($config['database']['testing']);
