<?php

require_once dirname(__FILE__).'/../../bootstrap/model.php';
require_once $_plugin_dir.'/lib/model/iceModelSpamControlPeer.php';

$t = new lime_test(0, new lime_output_color());
$t->diag('Testing iceModelSpamControlPeer');