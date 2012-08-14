<?php

/**
 * iceSpamControlValidatorSchema
 *
 */
class iceSpamControlValidatorSchema extends sfValidatorSchema
{

  /**
   * Required options:
   *  - fields: You must specify an associative array of form fields that
   *            correspondent to spam control fields
   *
   * Available options:
   *
   *  - credentials: For which credentials should the check be performed. By default,
   *           all credentials will be included in the spam check (do note that all
   *           credentials is dfferent from the "all" credential - it denotes a check
   *           that should be applied to the otehr credentials as well)
   *
   * @param     array $options
   * @param     array $messages
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addRequiredOption('fields');
    $this->addOption('credentials');
  }

  public function doClean($values)
  {
    return parent::doClean($values);
  }

}
