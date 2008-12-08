<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
 
$browser = new JobeetTestFunctional(new sfBrowser());
$browser->loadData();
 
$browser->info('1 - The homepage')->
  get('/')->
  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    info('  1.1 - Expired jobs are not listed')->
    checkElement('.jobs td.position:contains("expired")', false)->
 
    info(sprintf('  1.2 - Only %s jobs are listed for a category', sfConfig::get('app_max_jobs_on_homepage')))->
    checkElement('.category_programming tr', sfConfig::get('app_max_jobs_on_homepage'))->
 
    info('  1.3 - A category has a link to the category page only if too many jobs')->
    checkElement('.category_design .more_jobs', false)->
    checkElement('.category_programming .more_jobs')->
 
    info('  1.4 - Jobs are sorted by date')->
    checkElement(sprintf('.category_programming tr:first a[href*="/%d/"]', $browser->getMostRecentProgrammingJob()->getId()))->
    checkElement('.category_programming tr:last:contains("102")')->
  end()
;
 
$browser->info('2 - The job page')->
  info('  2.1 - Each job on the homepage is clickable and give detailed information')->
  click('Web Developer', array('position' => 1))->
  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'show')->
    isParameter('company_slug', 'sensio-labs')->
    isParameter('location_slug', 'paris-france')->
    isParameter('position_slug', 'web-developer')->
    isParameter('id', $browser->getMostRecentProgrammingJob()->getId())->
  end()->
 
  info('  2.2 - A non-existent job forwards the user to a 404')->
  get('/job/foo-inc/milano-italy/0/painter')->
  with('response')->isStatusCode(404)->
 
  info('  2.3 - An expired job page forwards the user to a 404')->
  get(sprintf('/job/sensio-labs/paris-france/%d/web-developer', $browser->getExpiredJob()->getId()))->
  with('response')->isStatusCode(404)
;
