<?php

/**
 * iceSpamControlValidatorSchema
 *
 */
class iceSpamControlValidatorSchema extends sfValidatorSchema
{

  /**
   * Remove the $fields variable from the constructor
   *
   * @param type $options
   * @param type $messages
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

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
   *  - force_skip_check:  boolean/callable - force spam check to be skipped
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
    $this->addOption('force_skip_check', false);

    $this->addMessage('spam', 'The form failed iceSpamControl check');
    $this->addMessage('spam_field', 'This field failed iceSpamControl check');
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

    // check for forcible skip spam check, eitehr directly or by callable
    $skip_check = $this->getOption('force_skip_check');
    if (true === $skip_check ||
        (is_callable($skip_check) && true === call_user_func($skip_check, $values))
    ) {
      // we are forced to skip the check by the form, just return the values
      return $values;
    }

    $localErrorSchema = new sfValidatorErrorSchema($this);

    foreach ($this->getOption('fields') as $form_field => $spam_field)
    {
      if (!array_key_exists($form_field, $values))
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
          throw new sfValidatorError($this, 'spam');
        }
        else
        {
          $localErrorSchema->addError(
            new sfValidatorError($this, 'spam_field'),
            $form_field
          );
        }
      }
    }

    if (count($localErrorSchema))
    {
      throw $localErrorSchema;
    }

    return $values;
  }

}
