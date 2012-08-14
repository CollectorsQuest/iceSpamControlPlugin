<?php

$fixtures = array(
    'spam_control.yml',
);

include_once dirname(__FILE__).'/../../bootstrap/model.php';
require_once $_plugin_dir.'/lib/validator/iceSpamControlValidatorSchema.class.php';

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing /lib/validator/iceSpamControlValidatorSchema.class.php');

$tests = array(
//  array($values, $pass, $test_message)
    array(array(
        'ip_number' => 'invalid',
        'email' => 'mail@example.com',
      ), false, 'Invalid IP throws error with validation'),

    array(array(
        'ip_number' => '127.0.0.1',
        'email' => 'mail@example.com',
      ), true, 'Valid values'),

    array(array(
        'ip_number' => '127.0.0.1',
        'email' => 'blocked@mail.com',
      ), false, 'Spam blocked'),
);

$v = new iceSpamControlValidatorSchema(null, array(
    'fields' => array(
       'ip_number' => 'ip',
       'email' => 'email',
    ),
    'validate' => true,
    'allow_extra_fields' => true,
    'filter_extra_fields' => false,
));

// fist test for expected runtime exception
try {
  $v->clean(array('ip_number' => '127.0.0.1'));
  $t->fail('Validator thows RuntimeException when badly configured');
} catch (RuntimeException $e)
{
  $t->pass('Validator thows RuntimeException when badly configured');
}


foreach ($tests as $test)
{
  list ($values, $pass, $message) = $test;

  try
  {
    $v->clean($values);
    $t->ok($pass, $message);
  }
  catch (sfValidatorError $e)
  {
    $t->ok(!$pass, $message);
  }
}

