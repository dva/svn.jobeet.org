<?php use_stylesheet('job.css') ?>
<?php slot('title', sprintf('%s is looking for a %s', $job->getCompany(), $job->getPosition())) ?>
<?php use_helper('Text') ?>
 
<?php if ($sf_request->getParameter('token') == $job->getToken()): ?>
  <?php include_partial('sfJobeetJob/admin', array('job' => $job)) ?>
<?php endif; ?>
 
<div id="job">
  <h1><?php echo $job->getCompany() ?></h1>
  <h2><?php echo $job->getLocation() ?></h2>
  <h3>
    <?php echo $job->getPosition() ?>
    <small> - <?php echo $job->getType() ?></small>
  </h3>
 
  <?php if ($job->getLogo()): ?>
    <div class="logo">
      <a href="<?php echo $job->getUrl() ?>">
        <img src="<?php echo $job->getLogo() ?>" alt="<?php echo $job->getCompany() ?> logo" />
      </a>
    </div>
  <?php endif; ?>
 
  <div class="description">
    <?php echo simple_format_text($job->getDescription()) ?>
  </div>
 
  <h4><?php echo __('How to apply?'); ?></h4>
 
  <p class="how_to_apply"><?php echo $job->getHowToApply() ?></p>
 
  <div class="meta">
    <small><?php echo __('posted on %date%', array('date' => $job->getCreatedAt('m/d/Y'))); ?></small>
  </div>

</div>
