<?php

// setup constants
$_SERVER['SYMFONY'] = isset($_SERVER['SYMFONY'])
  ? $_SERVER['SYMFONY']
  : dirname(__FILE__).'/../../../../lib/vendor/symfony/';

define('SF_DIR', $_SERVER['SYMFONY']);

require_once SF_DIR . 'test/bootstrap/unit.php';
require_once SF_DIR . 'lib/autoload/sfSimpleAutoload.class.php';

$autoload = sfSimpleAutoload::getInstance(sys_get_temp_dir().DIRECTORY_SEPARATOR.sprintf('sf_autoload_unit_ice_spam_control_plugin_%s.data', md5(__FILE__)));
$autoload->addDirectory(realpath(dirname(__FILE__).'/../../lib'));
$autoload->register();

$_test_dir = realpath(dirname(__FILE__).'/..');
$_plugin_dir = realpath($_test_dir . '/..');
$_root_dir = realpath($_test_dir . '/../../..');