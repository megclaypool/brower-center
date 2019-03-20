<?php
if ( $taxonomy['8'])
{
	menu_set_active_item('tenants'); //bp_set_global('tenant_node',true);
	bp_set_global( 'hard_css', 'tenants');
	echo('<p><a href="tenants">&lt; Back to tenant list</a></p>');
}
?>
<?php if (empty($node->iid)) echo('<div class="imageless">'); ?>
<?php if ($node->field_category[0]['view']): ?>
	<!--h3><?php print $node->field_category[0]['view']; ?></h3-->
<?php endif; ?>
	<h1 id="pretty-list-item-<?php echo $node->nid;?>"><?php print $node->title; ?></h1>
	<?php if ($node->field_location_time[0]['view']): ?>
	<h2><?php print nl2br($node->field_location_time[0]['view']); ?></h2>
	<?php endif; ?>
<?php print $node->content['body']['#value']; ?>
<?php if (($node->field_link_text[0]['view']) && ($node->field_link_url[0]['view'])): ?>
<p class="link"><a href="<?php print $node->field_link_url[0]['view'];?>"><?php print $node->field_link_text[0]['view']; ?></a></p>
<?php endif; ?>
<?php if (empty($node->iid)) echo('</div>'); ?>
<?php
/*
<div class="field field-type-text field-field-category">
  <h3 class="field-label">Category</h3>
  <div class="field-items">
      <div class="field-item"><?php print $node->field_category[0]['view'] ?></div>
  </div>
</div>

<div class="field field-type-text field-field-location-time">
  <h3 class="field-label">Location/time</h3>
  <div class="field-items">
      <div class="field-item"><?php print $node->field_location_time[0]['view'] ?></div>
  </div>
</div>
<!-- <?php print_r($node); ?> -->
*/
?>
