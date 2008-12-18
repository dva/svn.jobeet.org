<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * JobeetApplication filter form base class.
 *
 * @package    jobeet
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 13459 2008-11-28 14:48:12Z fabien $
 */
class BaseJobeetApplicationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'job_id'     => new sfWidgetFormPropelChoice(array('model' => 'JobeetJob', 'add_empty' => true)),
      'name'       => new sfWidgetFormFilterInput(),
      'email'      => new sfWidgetFormFilterInput(),
      'letter'     => new sfWidgetFormFilterInput(),
      'resume'     => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'job_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'JobeetJob', 'column' => 'id')),
      'name'       => new sfValidatorPass(array('required' => false)),
      'email'      => new sfValidatorPass(array('required' => false)),
      'letter'     => new sfValidatorPass(array('required' => false)),
      'resume'     => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('jobeet_application_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetApplication';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'job_id'     => 'ForeignKey',
      'name'       => 'Text',
      'email'      => 'Text',
      'letter'     => 'Text',
      'resume'     => 'Text',
      'created_at' => 'Date',
    );
  }
}
