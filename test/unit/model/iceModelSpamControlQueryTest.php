<?php

$fixtures = array(
    'spam_control.yml',
);

require_once dirname(__FILE__).'/../../bootstrap/model.php';
require_once $_plugin_dir.'/lib/model/iceModelSpamControlQuery.php';

$t = new lime_test(4, new lime_output_color());
$t->diag('Testing lib/model/iceModelSpamControlQuery');

$t->diag('->filterByCredentials()');

$q = iceModelSpamControlQuery::create()
  ->filterByValue('credentials')
  ->filterByCredentials(iceModelSpamControlPeer::CREDENTIALS_ALL);
$t->is($q->count(), 3,
  'Searching for all credentials returns all results, not only the literal "all"');

$q = iceModelSpamControlQuery::create()
  ->filterByValue('credentials')
  ->filterByCredentials(iceModelSpamControlPeer::CREDENTIALS_READ);
$t->is($q->count(), 2,
  'Searching for read credentials returns "read" + "all" credentials results, not only the literal "read"');

$q = iceModelSpamControlQuery::create()
  ->filterByValue('credentials')
  ->filterByCredentials(iceModelSpamControlPeer::CREDENTIALS_COMMENT);
$t->is($q->count(), 2,
  'Searching for comment credentials returns "comment" + "all" credentials results, not only the literal "read"');

$q = iceModelSpamControlQuery::create()
  ->filterByValue('credentials')
  ->filterByCredentials(iceModelSpamControlPeer::CREDENTIALS_COMMENT, Criteria::NOT_IN);
$t->is($q->count(), 1,
  'Searching for NOT comment credentials returns everything that is NOT "comment" or "all"');