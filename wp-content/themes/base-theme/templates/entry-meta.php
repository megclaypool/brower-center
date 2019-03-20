<?php if(get_post_type() == 'news') { ?>
<div class="byline author vcard">
<time class="updated" datetime="<?= get_the_time('c'); ?>"><?= get_the_date(); ?></time>
<?php if (get_field('publication')) { ?>
    <span class="publication"> | Publication: <?php the_field('publication'); ?></span>
<?php } ?>
<?php if (get_field('author')) { ?>
    <span class="publication"> | Author: <?php the_field('author'); ?></span>
<?php } ?>
</div>
<?php } ?>

<?php if (get_field('room_capacity')) { ?>
    <div class="byline author vcard">Room Capacity: <?php the_field('room_capacity'); ?></div>
<?php } ?>
<?php if(get_post_type() == 'program') { ?>
	<?php if (get_field('price')) { ?>
	    <div class="byline author vcard">Price: <?php the_field('price'); ?></div>
	<?php } ?>
<?php } ?>
<?php if(get_post_type() == 'exhibit') { ?>
<?php if (get_field('date')) { 
	?>
    <div class="byline author vcard"><?php the_field('date');  ?></div>
<?php } ?><?php } ?>
