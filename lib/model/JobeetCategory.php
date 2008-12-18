<?php

class JobeetCategory extends BaseJobeetCategory
{
  public function __toString()
  {
    return $this->getName();
  }

  public function getActiveJobs($max = 0)
  {
    $criteria = $this->getActiveJobsCriteria();
    $criteria->setLimit($max);

    return JobeetJobPeer::doSelect($criteria);
  }

  public function countActiveJobs()
  {
    $criteria = $this->getActiveJobsCriteria();
    $criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());

    return JobeetJobPeer::doCount($criteria);
  }
  
  public function getActiveJobsCriteria()
  {
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());

    return JobeetJobPeer::addActiveJobsCriteria($criteria);
  }

  public function getLatestPost()
  {
    $jobs = $this->getActiveJobs(1);
 
    return $jobs[0];
  }
}
