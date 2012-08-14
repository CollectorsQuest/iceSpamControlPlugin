<?php

/**
 * PluginiceModelSpamControlPeer
 */
class PluginiceModelSpamControlPeer extends BaseiceModelSpamControlPeer
{

  /**
   * Add a new ban or throttle
   *
   * @param     string $field
   * @param     string $value
   * @param     string $credentials
   *
   * @return    boolean
   * @throws    RuntimeException
   */
  protected static function addBanOrThrottle(
    $type,
    $field,
    $value,
    $credentials = 'read'
  ) {
    if (!in_array($type, array('ban', 'throttle')))
    {
      throw new RuntimeException(
        'You can add either a ban or a throttle; You supplied '. $type
      );
    }

    if (false === array_search($field, self::getValueSet(self::FIELD)))
    {
      throw new RuntimeException(sprintf(
        'Unknown field "%s", supported fields are: %s',
        $field,
        implode(', ', self::getValueSet(self::FIELD))
      ));
    }

    if (false === array_search($credentials, self::getValueSet(self::CREDENTIALS)))
    {
      throw new RuntimeException(sprintf(
        'Unknown credential "%s", supported credentials are: %s',
        $credentials,
        implode(', ', self::getValueSet(self::CREDENTIALS))
      ));
    }

    $spamControl = new iceModelSpamControl();
    $spamControl->setField($field);
    $spamControl->setValue($value);
    $spamControl->setCredentials($credentials);
    $spamControl->setIsBanned(true);

    try
    {
      $spamControl->save();
    }
    catch (PropelException $e)
    {
      return false;
    }

    return true;
  }

  /**
   * Add a new ban
   *
   * @param     string $field
   * @param     string $value
   * @param     string $credentials
   *
   * @return    boolean
   * @throws    RuntimeException
   */
  public static function addBan($field, $value, $credentials = 'read')
  {
    return self::addBanOrThrottle('ban', $field, $value, $credentials);
  }

  /**
   * Add a new throttle
   *
   * @param     string $field
   * @param     string $value
   * @param     string $credentials
   *
   * @return    boolean
   * @throws    RuntimeException
   */
  public static function addThrottle($field, $value, $credentials = 'read')
  {
    return self::addBanOrThrottle('throttle', $field, $value, $credentials);
  }

}
