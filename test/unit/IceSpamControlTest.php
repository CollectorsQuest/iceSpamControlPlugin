<?php

$fixtures = array(
    'spam_control.yml',
);

require_once dirname(__FILE__).'/../bootstrap/model.php';
require_once $_plugin_dir.'/lib/iceSpamControl.class.php';

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing lib/iceSpamControl.class.php');