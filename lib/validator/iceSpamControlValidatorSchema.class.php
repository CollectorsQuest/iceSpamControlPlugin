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
   *  - check:       banned/throttled
   *  - validate:    Whether to perform iceSpamControl validation on the value
   *  - credentials: For which credentials should the check be performed. By default,
   *           all credentials will be included in the spam check (do note that all
   *           credentials is dfferent from the "all" credential - it denotes a check
   *           that should be applied to the otehr credentials as well)
   *  - throw_global_error: Whether to throw a global error, or add errors on
   *                        separate fields
   *
   * @param     array $options
   * @param     array $messages
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addRequiredOption('fields');
    $this->addOption('check', 'banned');
    $this->addOption('validate', false);
    $this->addOption('credentials', iceSpamControl::CREDENTIALS_ALL);
    $this->addOption('throw_global_error', true);

    $this->addMessage('invalid', 'The form failed iceSpamControl check');
    $this->addMessage('invalid_field', 'This field failed iceSpamControl check');
  }

  public function doClean($values)
  {
    $check = $this->getOption('check');
    if (!in_array($check, array('banned', 'throttled')))
    {
      throw new RuntimeException(
        'iceSpamControlValidatorSchema: Check must be one of "banned" or "throttled"'
      );
    }

    $localErrorSchema = new sfValidatorErrorSchema($this);

    foreach ($this->getOption('fields') as $form_field => $spam_field)
    {
      if (!isset($values[$form_field]))
      {
        throw new RuntimeException(sprintf(
          'iceSpamControlValidatorSchema: You tried to filter for field %s which did not exist in the values to filter',
          $form_field
        ));
      }

      $method = 'is' . ucfirst($check);
      $is_banned = call_user_func_array(array('iceSpamControl', $method), array(
        $spam_field,
        $values[$form_field],
        $this->getOption('validate'),
        $this->getOption('credentials')
      ));

      if ($is_banned)
      {
        if ($this->getOption('throw_global_error'))
        {
          throw new sfValidatorError($this, 'invalid');
        }
        else
        {
          $localErrorSchema->addError(
            new sfValidatorError($this, 'invalid_field'),
            $form_field
          );
        }
      }
    }

    if (count($localErrorSchema))
    {
      throw $localErrorSchema;
    }

    return parent::doClean($values);
  }

}
