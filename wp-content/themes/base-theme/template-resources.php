<?php
/**
 * Template Name: Resources Template
 *
 * Use field_imports/landing-field-export.xml to import fields
 */
?>

<div class="entry-content">
<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
  <?php if( is_user_logged_in() ) {
  	get_template_part('templates/resources');
  } else { ?> 
	<script>
		window.location.replace("http://www.browercenter.org/residents");
	</script>
	<?php } ?>

<?php endwhile; ?>
</div>