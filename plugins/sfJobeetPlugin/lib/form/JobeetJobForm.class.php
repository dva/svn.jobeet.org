<?php

/**
 * JobeetJob form.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class JobeetJobForm extends BaseJobeetJobForm
{
	
  public function __construct(BaseObject $object = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($object, $options, false);
  }

  public function configure()
  {
    $this->removeFields();
 
    $this->validatorSchema['email'] = new sfValidatorEmail();
 
    $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
      'choices' => JobeetJobPeer::$types,
      'expanded' => true,
    ));
    $this->validatorSchema['type'] = new sfValidatorChoice(array(
      'choices' => array_keys(JobeetJobPeer::$types),
    ));
 
    $this->widgetSchema['logo'] = new sfWidgetFormInputFile(array(
      'label' => 'Company logo',
    ));
    $this->validatorSchema['logo'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/jobs',
      'mime_types' => 'web_images',
    ));
 
    $this->widgetSchema->setHelp('is_public', 'Whether the job can also be published on affiliate websites or not.');
    
    $this->widgetSchema->setNameFormat('job[%s]');
  }

  protected function removeFields()
  {
    unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['token'],
      $this['is_activated']
    );
  }
}