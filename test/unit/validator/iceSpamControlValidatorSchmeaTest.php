<?php
include_once dirname(__FILE__).'/../../bootstrap/unit.php';
require_once $_plugin_dir.'/lib/validator/iceSpamControlValidatorSchema.class.php';

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing /lib/validator/iceSpamControlValidatorSchema.class.php');

$tests = array(
//  array($value, $result, $test_message)
);

$v = new cqValidatorUSDtoCents();

foreach ($tests as $test)
{
  list($value, $result, $message) = $test;

  $t->is_deeply($v->clean($value), $result, '::clean() ' . $message);
}

