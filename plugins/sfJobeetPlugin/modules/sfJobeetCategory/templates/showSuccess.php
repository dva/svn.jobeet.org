<?php use_stylesheet('jobs.css') ?>
<?php include_partial('sfJobeetJob/list', array('jobs' => $pager->getResults())) ?>
 
<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination">
    <a href="<?php echo url_for('category', $category) ?>?page=1">
      <img src="/images/first.png" alt="First page" />
    </a>
 
    <a href="<?php echo url_for('category', $category) ?>?page=<?php echo $pager->getPreviousPage() ?>">
      <img src="/images/previous.png" alt="Previous page" title="Previous page" />
    </a>
 
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <?php echo $page ?>
      <?php else: ?>
        <a href="<?php echo url_for('category', $category) ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
      <?php endif; ?>
    <?php endforeach; ?>
 
    <a href="<?php echo url_for('category', $category) ?>?page=<?php echo $pager->getNextPage() ?>">
      <img src="/images/next.png" alt="Next page" title="Next page" />
    </a>
 
    <a href="<?php echo url_for('category', $category) ?>?page=<?php echo $pager->getLastPage() ?>">
      <img src="/images/last.png" alt="Last page" title="Last page" />
    </a>
  </div>
<?php endif; ?>
 
<div class="pagination_desc">
  <?php echo format_number_choice(
	    '[0]No job in this category|[1]One job in this category|(1,+Inf]%count% jobs in this category',
	    array('%count%' => '<strong>'.$pager->getNbResults().'</strong>'),
	    $pager->getNbResults()
	  )
	?>
 
  <?php if ($pager->haveToPaginate()): ?>
    - <?php echo __('page'); ?> <strong><?php echo $pager->getPage() ?>/<?php echo $pager->getLastPage() ?></strong>
  <?php endif; ?>
</div>
<div class="feed">
  <a href="<?php echo url_for('category', array('sf_subject' => $category, 'sf_format' => 'atom')) ?>"><?php echo __('RSS feed'); ?></a>
</div>
