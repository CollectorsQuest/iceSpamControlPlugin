<?php

$fixtures = array(
    'spam_control.yml',
);

require_once dirname(__FILE__).'/../../bootstrap/model.php';
require_once $_plugin_dir.'/lib/model/iceModelSpamControlPeer.php';

$t = new lime_test(6, new lime_output_color());
$t->diag('Testing lib/model/iceModelSpamControlPeer');

$t->diag('::addBan');
$t->ok(!iceModelSpamControlPeer::addBan('email', 'blocked@mail.com'),
  'Trying to add a ban for an existent ban doesn\'t work');
$t->ok(iceModelSpamControlPeer::addBan('email', 'blocked@mail.com', iceModelSpamControlPeer::CREDENTIALS_COMMENT),
  'Trying to add a ban for an existent with different credentials works');

try {
   iceModelSpamControlPeer::addBan('ggbg', 'ggbg-er');
   $t->fail('Add ban throws RuntimeException for non-existent fileds');
} catch (RuntimeException $e) {
   $t->pass('Add ban throws RuntimeException for non-existent fileds: '.$e->getMessage());
}

try {
   iceModelSpamControlPeer::addBan('email', 'ggbg-er', 'no-such-credential-yea');
   $t->fail('Add ban throws RuntimeException for non-existent credentials');
} catch (RuntimeException $e) {
   $t->pass('Add ban throws RuntimeException for non-existent credentials'.$e->getMessage());
}

$t->ok(!iceModelSpamControlPeer::addThrottle('email', 'blocked@mail.com'),
  'You can\'t add a throttle for a banned value (and vise-versa)');

$t->ok(!iceModelSpamControlPeer::addBan('email', 'throttled@mail.com'),
  'You can\'t add a throttle for a banned value (and vise-versa)');