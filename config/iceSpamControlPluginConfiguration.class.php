<?php

class iceSpamControlPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (in_array('iceSpamControlBackendModule', sfConfig::get('sf_enabled_modules', array())) && sfConfig::get('app_iceSpamControl_register_routes', true))
    {
      $this->dispatcher->connect('routing.load_configuration', array('iceSpamControlPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
    }
  }
}
