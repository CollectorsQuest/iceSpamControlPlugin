<?php

class iceSpamControlPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject(); // add plug-in routing rules on top of the existing ones
    $routing->prependRoute('spam_control', new icePropelRouteCollection(array(
        'name' => 'spam_control',
        'model' => 'iceModelSpamControl',
        'column' => 'id',
        'module' => 'iceSpamControlBackendModule',
        'prefix_path' => 'spam_control',
        'with_wildcard_routes' => true,
    )));
  }
}
