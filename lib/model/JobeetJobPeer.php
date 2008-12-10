<?php

class JobeetJobPeer extends BaseJobeetJobPeer
{
  
  static public $types = array(
      'full-time' => 'Full time',
      'part-time' => 'Part time',
      'freelance' => 'Freelance'
  );
  
  static public function doSelectActive(Criteria $criteria)
  {
    return self::doSelectOne(self::addActiveJobsCriteria($criteria));
  }
 
  static public function getActiveJobs(Criteria $criteria = null)
  {
    return self::doSelect(self::addActiveJobsCriteria($criteria));
  }
 
  static public function countActiveJobs(Criteria $criteria = null)
  {
    return self::doCount(self::addActiveJobsCriteria($criteria));
  }
 
  static public function addActiveJobsCriteria(Criteria $criteria = null)
  {
    if (is_null($criteria))
    {
      $criteria = new Criteria();
    }
 
    $criteria->add(self::EXPIRES_AT, time(), Criteria::GREATER_THAN);
    $criteria->addDescendingOrderByColumn(self::CREATED_AT);
    $criteria->add(self::IS_ACTIVATED, true);
 
    return $criteria;
  }



}
