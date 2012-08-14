<?php

include __DIR__ .'/unit.php';

require_once $_root_dir . '/config/ProjectConfiguration.class.php';
$sf_configuration = ProjectConfiguration::hasActive() ? ProjectConfiguration::getActive() : new ProjectConfiguration(realpath($_root_dir));

/** @var $sf_configuration sfProjectConfiguration */
$sf_application = $sf_configuration->getApplicationConfiguration(isset($app) ? $app : 'frontend', 'test', isset($debug) ? $debug : true);
$sf_context = sfContext::createInstance($sf_application);
