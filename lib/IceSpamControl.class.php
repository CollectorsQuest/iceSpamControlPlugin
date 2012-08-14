<?php

/**
 * IceSpamControl
 */
class IceSpamControl
{
  const CREDENTIALS_ALL     = iceModelSpamControlPeer::CREDENTIALS_ALL;
  const CREDENTIALS_READ    = iceModelSpamControlPeer::CREDENTIALS_READ;
  const CREDENTIALS_CREATE  = iceModelSpamControlPeer::CREDENTIALS_CREATE;
  const CREDENTIALS_EDIT    = iceModelSpamControlPeer::CREDENTIALS_EDIT;
  const CREDENTIALS_COMMENT = iceModelSpamControlPeer::CREDENTIALS_COMMENT;

  /**
   * Check if a given filed/value combination is banned
   *
   * @param     string $field
   * @param     mixed $value
   * @param     boolean $validate
   *
   * @return    boolean
   */
  public static function isBanned(
    $field,
    $value,
    $validate = false,
    $credentials = self::CREDENTIALS_READ
  ) {
    if (is_string($value))
    {
      $value = trim($value);
    }

    if (empty($value))
    {
      return false;
    }

    if ($validate && false === ($values = self::validateField($field, $value)))
    {
      return true;
    }
    else
    {
      $values = isset($values) ? $values : array($value);
    }

    foreach ($values as $value)
    {
      $is_banned = (boolean) iceModelSpamControlQuery::create()
        ->filterByField($field)
        ->filterByValue($value)
        ->filterByCredentials($credentials)
        ->filterByIsBanned(true)
        ->count();

      if ($is_banned)
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Check if a given field/value combination is banned
   *
   * @param     string $field
   * @param     mixed $value
   * @param     string $credentials
   *
   * @return    boolean
   */
  public static function isThrottled(
    $field,
    $value,
    $validate = false,
    $credentials = self::CREDENTIALS_READ
  ) {
    if (is_string($value))
    {
      $value = trim($value);
    }

    if (empty($value))
    {
      return false;
    }

    if ($validate && false === ($values = self::validateField($field, $value)))
    {
      return true;
    }
    else
    {
      $values = isset($values) ? $values : array($value);
    }

    foreach ($values as $value)
    {
      $is_throttled = (boolean) iceModelSpamControlQuery::create()
        ->filterByField($field)
        ->filterByValue($value)
        ->filterByCredentials($credentials)
        ->filterByIsThrottled(true)
        ->count();

      if ($is_throttled)
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Add a new spam ban
   *
   * @param     string $field
   * @param     mixed $value
   * @param     boolean $validate
   * @param     string $credentials
   *
   * @return    boolean Was ban successfully added
   */
  public static function ban(
    $field,
    $value,
    $validate = false,
    $credentials = self::CREDENTIALS_READ
  ) {
    if ($validate && false === ($values = self::validateField($field, $value)))
    {
      return false;
    }
    else
    {
      $values = isset($values) ? $values : array($value);
    }

    $ret = true;
    foreach ($values as $value)
    {
      $ret = $ret && iceModelSpamControlPeer::addBan($field, $value, $credentials);
    }

    return $ret;
  }

  /**
   * Add a new spam throttle
   *
   * @param     string $field
   * @param     mixed $value
   * @param     boolean $validate
   * @param     string $credentials
   *
   * @return    boolean Was throttle successfully added
   */
  public static function throttle(
    $field,
    $value,
    $validate = false,
    $credentials = self::CREDENTIALS_READ
  ) {
    if ($validate && false === ($values = self::validateField($field, $value)))
    {
      return false;
    }
    else
    {
      $values = isset($values) ? $values : array($value);
    }

    $ret = true;
    foreach ($values as $value)
    {
      $ret = $ret && iceModelSpamControlPeer::addThrottle(
        $field, $value, $credentials
      );
    }

    return $ret;
  }

  /**
   * Validate a field by IceSpamControl standards
   *
   * @param     string $field
   * @param     mixed $value
   *
   * @return    mixed false if unsuccessful validation or array of validated values
   */
  public static function validateField($field, $value)
  {
    switch ($field)
    {
      case 'email':
        if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $value))
        {
          return false;
        }
        else
        {
          return array($value);
        }
        break;

      case 'phone':
        $count = 0;
        $values = IceStatic::extractPhoneNumbers($value, false, $count);

        // This will clean the resulting numbers from everything but numbers and "+"
        $clean = create_function('$a', 'return preg_replace("/[^0-9\+]+/", "", $a);');
        $values = array_map($clean, $values);
        $values = array_filter($values);

        if ($count == 0 && empty($values))
        {
          return false;
        }
        else
        {
          return $values;
        }
        break;

      case 'ip':
        if (filter_var($value, FILTER_VALIDATE_IP))
        {
          return array($value);
        }
        else
        {
          return false;
        }
        break;

      default:
        return array($value);
        break;
    }
  }

}
