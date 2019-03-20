<?php
/**
 * Template Name: Listing Page Template
 *
 * Use field_imports/listing-field-export.xml to import fields
 */
?>

<div class="entry-content">
<?php while (have_posts()) : the_post(); ?>
  <?php //get_template_part( 'templates/page-header' ); ?>
  <?php the_content(); ?>
<?php endwhile; ?>
  <?php get_template_part('templates/listing'); ?>
</div>