<h2><a href="about/inthenews">In the News</a></h2>
<h1><?php print $node->title; ?></h1>
<p><strong><?php print $node->field_publication_date[0]['view']; ?></strong><?php if (( !empty($node->field_author[0]['view'])) && ( !empty($node->field_author[0]['view'])) && ( !empty($node->field_author[0]['view']))): ?>
<br /><em>By <?php print $node->field_author[0]['view']; ?></em>
<?php endif;?></p>
<?php print $node->field_article[0]['view']; ?>

