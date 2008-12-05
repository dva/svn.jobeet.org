<?php use_stylesheet('jobs.css') ?>
 
<div id="jobs">
  <table class="jobs">
    <?php foreach ($jobeet_job_list as $i => $job): ?>
      <tr class="<?php echo fmod($i, 2) ? 'even' : 'odd' ?>">
        <td><?php echo $job->getLocation() ?></td>
        <td>
          <a href="<?php echo url_for(array('sf_route' => 'job_show_user', 'sf_subject' => $job)) ?>">
            <?php echo $job->getPosition() ?>
          </a>
        </td>
        <td><?php echo $job->getCompany() ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
