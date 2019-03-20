<?php
/**
 * Template Name: Staff Template
 *
 * Use field_imports/staff-field-export.xml to import fields
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div class="entry-content">
    	<?php the_content(); ?>
    </div><!--./entry-content-->
    <section class="staff">
	    <?php get_template_part('templates/staff'); ?>
    </section>
  </article>

<?php endwhile; /* end origin loop*/?>	