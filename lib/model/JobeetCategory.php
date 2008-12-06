<?php

class JobeetCategory extends BaseJobeetCategory
{
  public function __toString()
  {
    return $this->getName();
  }

  public function getActiveJobs($max = 0)
  {
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());
    $criteria->setLimit($max);

    return JobeetJobPeer::getActiveJobs($criteria);
  }

}
