<?php

/**
 * JobeetJob form base class.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseJobeetJobForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'category_id'               => new sfWidgetFormPropelChoice(array('model' => 'JobeetCategory', 'add_empty' => false)),
      'type'                      => new sfWidgetFormInput(),
      'company'                   => new sfWidgetFormInput(),
      'logo'                      => new sfWidgetFormInput(),
      'url'                       => new sfWidgetFormInput(),
      'position'                  => new sfWidgetFormInput(),
      'location'                  => new sfWidgetFormInput(),
      'description'               => new sfWidgetFormTextarea(),
      'how_to_apply'              => new sfWidgetFormTextarea(),
      'token'                     => new sfWidgetFormInput(),
      'is_public'                 => new sfWidgetFormInputCheckbox(),
      'is_activated'              => new sfWidgetFormInputCheckbox(),
      'email'                     => new sfWidgetFormInput(),
      'expires_at'                => new sfWidgetFormDateTime(),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'jobeet_job_affiliate_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'JobeetAffiliate')),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorPropelChoice(array('model' => 'JobeetJob', 'column' => 'id', 'required' => false)),
      'category_id'               => new sfValidatorPropelChoice(array('model' => 'JobeetCategory', 'column' => 'id')),
      'type'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'company'                   => new sfValidatorString(array('max_length' => 255)),
      'logo'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'                       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'position'                  => new sfValidatorString(array('max_length' => 255)),
      'location'                  => new sfValidatorString(array('max_length' => 255)),
      'description'               => new sfValidatorString(),
      'how_to_apply'              => new sfValidatorString(),
      'token'                     => new sfValidatorString(array('max_length' => 255)),
      'is_public'                 => new sfValidatorBoolean(),
      'is_activated'              => new sfValidatorBoolean(),
      'email'                     => new sfValidatorString(array('max_length' => 255)),
      'expires_at'                => new sfValidatorDateTime(),
      'created_at'                => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                => new sfValidatorDateTime(array('required' => false)),
      'jobeet_job_affiliate_list' => new sfValidatorPropelChoiceMany(array('model' => 'JobeetAffiliate', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'JobeetJob', 'column' => array('token')))
    );

    $this->widgetSchema->setNameFormat('jobeet_job[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetJob';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['jobeet_job_affiliate_list']))
    {
      $values = array();
      foreach ($this->object->getJobeetJobAffiliates() as $obj)
      {
        $values[] = $obj->getAffiliateId();
      }

      $this->setDefault('jobeet_job_affiliate_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveJobeetJobAffiliateList($con);
  }

  public function saveJobeetJobAffiliateList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['jobeet_job_affiliate_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(JobeetJobAffiliatePeer::JOB_ID, $this->object->getPrimaryKey());
    JobeetJobAffiliatePeer::doDelete($c, $con);

    $values = $this->getValue('jobeet_job_affiliate_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new JobeetJobAffiliate();
        $obj->setJobId($this->object->getPrimaryKey());
        $obj->setAffiliateId($value);
        $obj->save();
      }
    }
  }

}
