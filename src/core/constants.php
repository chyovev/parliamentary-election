<?php
define('DS',              DIRECTORY_SEPARATOR);
define('VENDOR_PATH',     realpath(dirname(__FILE__) . '/../../vendor'));
define('PROPEL_PATH',     realpath(dirname(__FILE__) . '/../models/propel'));
define('SMARTY_PATH',     realpath(dirname(__FILE__) . '/smarty'));
define('TEMPLATES_PATH',  realpath(dirname(__FILE__) . '/../views'));
define('ROOT',            dirname(dirname(dirname(__FILE__))));
define('WEBROOT',         substr(ROOT, strlen($_SERVER['DOCUMENT_ROOT'])) . DS);
