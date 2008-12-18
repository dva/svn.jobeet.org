<?php

class JobeetCategoryPeer extends BaseJobeetCategoryPeer
{
  static public function getWithJobs()
  {
    $criteria = new Criteria();
    $criteria->addJoin(self::ID, JobeetJobPeer::CATEGORY_ID);
    $criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
    $criteria->setDistinct();
    $criteria->add(JobeetJobPeer::IS_ACTIVATED, true);
 
    return self::doSelect($criteria);
  }

	static public function getForSlug($slug)
	{
	  $criteria = new Criteria();
	  $criteria->addJoin(JobeetCategoryI18nPeer::ID, self::ID);
	  $criteria->add(JobeetCategoryI18nPeer::CULTURE, 'en');
	  $criteria->add(JobeetCategoryI18nPeer::SLUG, $slug);

	  return self::doSelectOne($criteria);
	}

  static public function doSelectForSlug($parameters)
  {
    $criteria = new Criteria();
    $criteria->addJoin(JobeetCategoryI18nPeer::ID, JobeetCategoryPeer::ID);
    $criteria->add(JobeetCategoryI18nPeer::CULTURE, $parameters['sf_culture']);
    $criteria->add(JobeetCategoryI18nPeer::SLUG, $parameters['slug']);
 
    return self::doSelectOne($criteria);
  }

}
