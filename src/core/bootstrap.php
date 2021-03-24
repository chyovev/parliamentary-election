<?php
// bootstrap.php gets included in the public/index.php file
// where all requests are sent to.
//
// bootstrap.php calls the following files:
//
//     1) constants.php
//     2) vendor/autoload.php
//     3) src/models/propel/generated-conf/config.php (DB config)
//
// The first one contains constant declarations and DB/email parameters.
// autoload.php on the other hand is set up to load all vendor-related files,
// but also other custom classes and files (see composer.json autoload declaration).

session_start();

require_once('constants.php');

require_once(VENDOR_PATH . '/autoload.php');
require_once(PROPEL_PATH . '/generated-conf/config.php');
