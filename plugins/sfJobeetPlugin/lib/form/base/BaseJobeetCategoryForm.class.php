<?php

/**
 * JobeetCategory form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseJobeetCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'jobeet_category_affiliate_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'JobeetAffiliate')),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorPropelChoice(array('model' => 'JobeetCategory', 'column' => 'id', 'required' => false)),
      'jobeet_category_affiliate_list' => new sfValidatorPropelChoiceMany(array('model' => 'JobeetAffiliate', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jobeet_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetCategory';
  }

  public function getI18nModelName()
  {
    return 'JobeetCategoryI18n';
  }

  public function getI18nFormClass()
  {
    return 'JobeetCategoryI18nForm';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['jobeet_category_affiliate_list']))
    {
      $values = array();
      foreach ($this->object->getJobeetCategoryAffiliates() as $obj)
      {
        $values[] = $obj->getAffiliateId();
      }

      $this->setDefault('jobeet_category_affiliate_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveJobeetCategoryAffiliateList($con);
  }

  public function saveJobeetCategoryAffiliateList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['jobeet_category_affiliate_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(JobeetCategoryAffiliatePeer::CATEGORY_ID, $this->object->getPrimaryKey());
    JobeetCategoryAffiliatePeer::doDelete($c, $con);

    $values = $this->getValue('jobeet_category_affiliate_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new JobeetCategoryAffiliate();
        $obj->setCategoryId($this->object->getPrimaryKey());
        $obj->setAffiliateId($value);
        $obj->save();
      }
    }
  }

}
