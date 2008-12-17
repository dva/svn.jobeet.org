<?php

/**
 * job actions.
 *
 * @package    jobeet
 * @subpackage job
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class jobActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->categories = JobeetCategoryPeer::getWithJobs();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->job = $this->getRoute()->getObject();
    
    $this->getUser()->addJobToHistory($this->job);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $jobeet_job = $this->getRoute()->getObject();
    $this->form = new JobeetJobForm($jobeet_job);
    
    $this->forward404If($jobeet_job->getIsActivated());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid())
    {
      $job = $form->save();

      $this->redirect($this->generateUrl('job_show', $job));
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($jobeet_job = JobeetJobPeer::retrieveByPk($request->getParameter('id')), sprintf('Object jobeet_job does not exist (%s).', $request->getParameter('id')));
    $jobeet_job->delete();

    $this->redirect('job/index');
  }
  
  public function executePublish(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $job = $this->getRoute()->getObject();
    $job->publish();

    $this->getUser()->setFlash('notice', sprintf('Your job is now online for %s days.', sfConfig::get('app_active_days')));

    $this->redirect($this->generateUrl('job_show_user', $job));
  }

  public function executeExtend(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $job = $this->getRoute()->getObject();
    $this->forward404Unless($job->extend());

    $this->getUser()->setFlash('notice', sprintf('Your job validity has been extend until %s.', $job->getExpiresAt('m/d/Y')));

    $this->redirect($this->generateUrl('job_show_user', $job));
  }

  public function executeSearch(sfWebRequest $request)
  {
	  if (!$query = $request->getParameter('query'))
	  {
	    return $this->forward('job', 'index');
	  }

	  $this->jobs = JobeetJobPeer::getForLuceneQuery($query);

	  if ($request->isXmlHttpRequest())
	  {
	    if ('*' == $query || !$this->jobs)
	    {
	      return $this->renderText('No results.');
	    }
	    else
	    {
	      return $this->renderPartial('job/list', array('jobs' => $this->jobs));
	    }
	  }
  }
}
