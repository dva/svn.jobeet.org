<?php

/**
 * affiliate actions.
 *
 * @package    jobeet
 * @subpackage affiliate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class affiliateActions extends sfActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new JobeetAffiliateForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new JobeetAffiliateForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

	public function executeWait()
	{
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $jobeet_affiliate = $form->save();

      $this->redirect($this->generateUrl('affiliate_wait', $jobeet_affiliate));
    }
  }

	  public function executeListActivate()
	  {
	    $affiliate = $this->getRoute()->getObject();
	    $affiliate->activate();

	    // send an email to the affiliate
	    ProjectConfiguration::registerZend();
	    $mail = new Zend_Mail();
	    $mail->setBodyText(<<<EOF
Your Jobeet affiliate account has been activated.

Your token is {$affiliate->getToken()}.

The Jobeet Bot.
EOF
);
	    $mail->setFrom('jobeet@example.com', 'Jobeet Bot');
	    $mail->addTo($affiliate->getEmail());
	    $mail->setSubject('Jobeet affiliate token');
	    $mail->send();

	    $this->redirect('@jobeet_affiliate');
	  }
}
