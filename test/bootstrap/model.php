<?php

include __DIR__ .'/unit.php';

// we need SQLite for model tests
if (!extension_loaded('SQLite') && !extension_loaded('pdo_SQLite'))
{
  echo "SQLite extension is required to run unit tests\n";
  return false;
}


sfConfig::set('sf_base_root_dir', $_root_dir);

if (!isset($root_dir))
{
  $root_dir = realpath(dirname(__FILE__).sprintf('/../%s/fixtures', isset($type) ? $type : 'model'));
}
if (!isset($app))
{
  $app = 'frontend';
}
require_once $root_dir.'/config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);


// setup cleanup/shutdown
function iceSpamControlPlugin_cleanup()
{
  sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
}
iceSpamControlPlugin_shutdown();
register_shutdown_function('iceSpamControlPlugin_shutdown');

function iceSpamControlPlugin_shutdown()
{
  try
  {
    iceSpamControlPlugin_cleanup();
  }
  catch (Exception $x)
  {
    // http://bugs.php.net/bug.php?id=33598
    echo $x.PHP_EOL;
  }
}


// run propel generator tasks if necessary
if (!isset($init_propel) || true == $init_propel)
{
  $configuration->initializePropel($app);
}
if (isset($fixtures))
{
  $configuration->loadFixtures($fixtures);
}