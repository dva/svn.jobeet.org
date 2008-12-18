<?php

class JobeetCategoryI18n extends BaseJobeetCategoryI18n
{
	public function setName($name)
  {
    parent::setName($name);

    $this->setSlug(Jobeet::slugify($name));
  }
}
