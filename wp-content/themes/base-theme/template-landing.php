<?php
/**
 * Template Name: Landing Page Template
 *
 * Use field_imports/landing-field-export.xml to import fields
 */
?>

<div class="entry-content">
<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
  <?php get_template_part('templates/landing'); ?>
<?php endwhile; ?>
</div>