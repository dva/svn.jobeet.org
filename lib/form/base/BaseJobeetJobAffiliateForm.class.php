<?php

/**
 * JobeetJobAffiliate form base class.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseJobeetJobAffiliateForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'job_id'       => new sfWidgetFormInputHidden(),
      'affiliate_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'job_id'       => new sfValidatorPropelChoice(array('model' => 'JobeetJob', 'column' => 'id', 'required' => false)),
      'affiliate_id' => new sfValidatorPropelChoice(array('model' => 'JobeetAffiliate', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jobeet_job_affiliate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetJobAffiliate';
  }


}
