<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
 
$browser = new JobeetTestFunctional(new sfBrowser());
$browser->loadData();

$browser->setTester('propel', 'sfTesterPropel');
 
$browser->info('1 - The homepage')->
  get('/en/')->
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
  click('Web Developer', array('position' => 0))->
  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'show')->
    isParameter('company_slug', 'sensio-labs')->
    isParameter('location_slug', 'paris-france')->
    isParameter('position_slug', 'web-developer')->
    isParameter('id', $browser->getMostRecentProgrammingJob()->getId())->
  end()->
 
  info('  2.2 - A non-existent job forwards the user to a 404')->
  get('/en/job/foo-inc/milano-italy/0/painter')->
  with('response')->isStatusCode(404)->
 
  info('  2.3 - An expired job page forwards the user to a 404')->
  get(sprintf('/en/job/sensio-labs/paris-france/%d/web-developer', $browser->getExpiredJob()->getId()))->
  with('response')->isStatusCode(404)
;

$browser->info('3 - Post a Job page')->
  info('  3.1 - Submit a Job')->
 
  get('/en/job/new')->
  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'new')->
  end()
;

$browser->info('3 - Post a Job page')->
  info('  3.1 - Submit a Job')->
 
  get('/en/job/new')->
  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'new')->
  end()->
 
  click('Preview your job', array('job' => array(
    'company'      => 'Sensio Labs',
    'url'          => 'http://www.sensio.com/',
    'logo'         => sfConfig::get('sf_uploads_dir').'/jobs/sensio-labs.gif',
    'position'     => 'Developer',
    'location'     => 'Atlanta, USA',
    'description'  => 'You will work with symfony to develop websites for our customers.',
    'how_to_apply' => 'Send me an email',
    'email'        => 'for.a.job@example.com',
    'is_public'    => false,
    'type'         => 'full-time',
  )))->
  
  with('form')->begin()->
    hasErrors(false)->
  end()->
  
  isRedirected()->
  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'job')->
    isParameter('action', 'show')->
  end()->

  with('propel')->begin()->
    check('JobeetJob', array(
      'location'     => 'Atlanta, USA',
      'is_activated' => false,
      'is_public'    => false,
    ))->
  end()
;

$browser->
  info('  3.2 - Submit a Job with invalid values')->
 
  get('/en/job/new')->
  click('Preview your job', array('job' => array(
    'company'      => 'Sensio Labs',
    'position'     => 'Developer',
    'location'     => 'Atlanta, USA',
    'email'        => 'not.an.email',
  )))->
 
  with('form')->begin()->
    hasErrors(4)->
    isError('description', 'required')->
    isError('how_to_apply', 'required')->
    isError('email', 'invalid')->
  end()
;

$browser->info('  3.3 - On the preview page, you can publish the job')->
  createJob(array('position' => 'FOO1'))->
  click('Publish', array(), array('method' => 'put', '_with_csrf' => true))->
 
  with('propel')->begin()->
    check('JobeetJob', array(
      'position'     => 'FOO1',
      'is_activated' => true,
    ))->
  end()
;

$browser->info('  3.4 - On the preview page, you can delete the job')->
  createJob(array('position' => 'FOO2'))->
  click('Delete', array(), array('method' => 'delete', '_with_csrf' => true))->
 
  with('propel')->begin()->
    check('JobeetJob', array(
      'position' => 'FOO2',
    ), false)->
  end()
;

$browser->info('  3.5 - When a job is published, it cannot be editing anymore')->
  createJob(array('position' => 'FOO3'), true)->
  get(sprintf('/en/job/%s/edit', $browser->getJobByPosition('FOO3')->getToken()))->
 
  with('response')->begin()->
    isStatusCode(404)->
  end()
;

$browser->info('  3.6 - A job validity cannot be extended before the job expires soon')->
  createJob(array('position' => 'FOO4'), true)->
  call(sprintf('/en/job/%s/extend', $browser->getJobByPosition('FOO4')->getToken()), 'put', array('_with_csrf' => true))->
  with('response')->begin()->
    isStatusCode(404)->
  end()
;
 
$browser->info('  3.7 - A job validity can be extended when the job expires soon')->
  createJob(array('position' => 'FOO5'), true)
;
 
$job = $browser->getJobByPosition('FOO5');
$job->setExpiresAt(time());
$job->save();
 
$browser->
  call(sprintf('/en/job/%s/extend', $job->getToken()), 'put', array('_with_csrf' => true))->
  with('response')->isRedirected()
;

$job->reload();
$browser->test()->is(
  $job->getExpiresAt('y/m/d'),
  date('y/m/d', time() + 86400 * sfConfig::get('app_active_days'))
);

$browser->
  info('4 - User job history')->
 
  loadData()->
  restart()->
 
  info('  4.1 - When the user access a job, it is added to its history')->
  get('/en/')->
  click('Web Developer', array('position' => 1))->
  get('/en/')->
  with('user')->begin()->
    isAttribute('job_history', array($browser->getMostRecentProgrammingJob()->getId()))->
  end()->
 
  info('  4.2 - A job is not added twice in the history')->
  click('Web Developer', array('position' => 1))->
  get('/en/')->
  with('user')->begin()->
    isAttribute('job_history', array($browser->getMostRecentProgrammingJob()->getId()))->
  end()
;

$browser->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
$browser->
  info('5 - Live search')->
 
  get('/en/search?query=sens*')->
  with('response')->begin()->
    checkElement('table tr', 2)->
  end()
;

$browser->setHttpHeader('ACCEPT_LANGUAGE', 'fr_FR,fr,en;q=0.7');
$browser->
  info('6 - User culture')->
 
  restart()->
 
  info('  6.1 - For the first request, symfony guess the best culture')->
  get('/')->
  isRedirected()->followRedirect()->
  with('user')->isCulture('fr')->
 
  info('  6.2 - Available cultures are en and fr')->
  get('/it/')->
  with('response')->isStatusCode(404)
;
 
$browser->setHttpHeader('ACCEPT_LANGUAGE', 'en,fr;q=0.7');
$browser->
  info('  6.3 - The culture guessing is only for the first request')->
 
  get('/')->
  isRedirected()->followRedirect()->
  with('user')->isCulture('fr')
;