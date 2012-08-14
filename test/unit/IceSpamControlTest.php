<?php

$fixtures = array(
    'spam_control.yml',
);

require_once dirname(__FILE__).'/../bootstrap/model.php';
require_once $_plugin_dir.'/lib/iceSpamControl.class.php';

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing lib/iceSpamControl.class.php');

$t->diag('::validateField()');
$t->ok(IceSpamControl::validateField('email', 'test@mail.com'));
$t->ok(!IceSpamControl::validateField('email', 'testificate'));
$t->ok(IceSpamControl::validateField('phone', '12341-3124'));
$t->ok(!IceSpamControl::validateField('phone', 'ggbg'));
$t->ok(IceSpamControl::validateField('ip', '88.88.88.88'));
$t->ok(!IceSpamControl::validateField('ip', '299.231.123.13'));

$t->diag('::isBanned()');
$t->ok(IceSpamControl::isBanned('email', 'blocked@mail.com'));
$t->ok(!IceSpamControl::isBanned('email', 'clean@mail.com'));

$t->diag('::isBanned() + credentials');
$t->ok(IceSpamControl::isBanned('email', 'blocked@mail.com', $validate = false, $credentials = iceSpamControl::CREDENTIALS_ALL));
$t->ok(IceSpamControl::isBanned('email', 'blocked+comment@mail.com', $validate = false, $credentials = iceSpamControl::CREDENTIALS_ALL));
$t->ok(IceSpamControl::isBanned('email', 'blocked+comment@mail.com', $validate = false, $credentials = iceSpamControl::CREDENTIALS_COMMENT));
$t->ok(!IceSpamControl::isBanned('email', 'blocked+comment@mail.com', $validate = false, $credentials = iceSpamControl::CREDENTIALS_READ));
