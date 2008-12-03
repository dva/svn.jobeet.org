<?php

/**
 * JobeetApplication form base class.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseJobeetApplicationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'job_id'     => new sfWidgetFormPropelChoice(array('model' => 'JobeetJob', 'add_empty' => false)),
      'name'       => new sfWidgetFormInput(),
      'email'      => new sfWidgetFormInput(),
      'letter'     => new sfWidgetFormTextarea(),
      'resume'     => new sfWidgetFormInput(),
      'created_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'JobeetApplication', 'column' => 'id', 'required' => false)),
      'job_id'     => new sfValidatorPropelChoice(array('model' => 'JobeetJob', 'column' => 'id')),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'email'      => new sfValidatorString(array('max_length' => 255)),
      'letter'     => new sfValidatorString(array('required' => false)),
      'resume'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jobeet_application[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetApplication';
  }


}
