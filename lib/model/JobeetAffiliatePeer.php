<?php

class JobeetAffiliatePeer extends BaseJobeetAffiliatePeer
{

  static public function getByToken($token)
  {
    $criteria = new Criteria();
    $criteria->add(self::TOKEN, $token);
 
    return self::doSelectOne($criteria);
  }	

  static public function countToBeActivated()
  {
    $criteria = new Criteria();
    $criteria->add(self::IS_ACTIVE, 0);
 
    return self::doCount($criteria);
  }
}
