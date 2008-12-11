<?php

class JobeetAffiliate extends BaseJobeetAffiliate
{
  public function __toString()
  {
    return $this->getUrl();
  }

  public function save(PropelPDO $con = null)
  {
    if (!$this->getToken())
    {
      $this->setToken(sha1($this->getEmail().rand(11111, 99999)));
    }
 
    return parent::save($con);
  }

  public function getActiveJobs()
  {
    $cas = $this->getJobeetCategoryAffiliates();
    $categories = array();
    foreach ($cas as $ca)
    {
      $categories[] = $ca->getCategoryId();
    }
 
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::CATEGORY_ID, $categories, Criteria::IN);
    JobeetJobPeer::addActiveJobsCriteria($criteria);
 
    return JobeetJobPeer::doSelect($criteria);
  }

  public function activate()
  {
    $this->setIsActive(true);
 
    return $this->save();
  }
 
  public function deactivate()
  {
    $this->setIsActive(false);
 
    return $this->save();
  }
}
