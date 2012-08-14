<?php

/**
 * PluginiceModelSpamControlQuery
 */
class PluginiceModelSpamControlQuery extends BaseiceModelSpamControlQuery
{

  public function findOneByIpAddress($v, PropelPDO $con = null)
  {
    return $this->filterByIpAddress($v, Criteria::EQUAL)
                ->findOne($con);
  }

  public function filterByIpAddress($v, $comparison = Criteria::EQUAL)
  {
    return $this->filterByField('ip')
                ->filterByValue($v, $comparison);
  }

  public function findOneByEmail($v, PropelPDO $con = null)
  {
    return $this->filterByEmail($v, Criteria::EQUAL)
                ->findOne($con);
  }

  public function filterByEmail($v, $comparison = Criteria::EQUAL)
  {
    return $this->filterByField('email')
                ->filterByValue($v, $comparison);
  }

  public function findOneByPhoneNumber($v, PropelPDO $con = null)
  {
    return $this->filterByPhoneNumber($v, Criteria::EQUAL)
                ->findOne($con);
  }

  public function filterByPhoneNumber($v, $comparison = Criteria::EQUAL)
  {
    return $this->filterByField('phone')
                ->filterByValue($v, $comparison);
  }

  public function findOneBySessionId($v, PropelPDO $con = null)
  {
    return $this->filterBySessionId($v, Criteria::EQUAL)
                ->findOne($con);
  }

  public function filterBySessionId($v, $comparison = Criteria::EQUAL)
  {
    return $this->filterByField('session')
                ->filterByValue($v, $comparison);
  }

  /**
   * Filter by credentials; Will include the "all" credential in the search if
   * searching for one of "read", "create", "edit", "comment"
   *
   * If searching for "all", will discard any filtering based on credentials
   *
   * @param     string $credentials
   * @return    PluginiceModelSpamControlQuery
   */
  public function filterByCredentials($credentials = null, $comparison = Criteria::IN)
  {
    // if we are given all credentials, do not filter by credentials at all
    if (
      null === $credentials ||
      iceModelSpamControlPeer::CREDENTIALS_ALL == $credentials
    ) {
      return $this;
    }
    // else filter with ALL added
    else
    {
      $credentials = array(
          iceModelSpamControlPeer::CREDENTIALS_ALL,
          $credentials
      );

      return parent::filterByCredentials($credentials, $comparison);
    }
  }

}
