<?php
/**
 * Template Name: Login Template
 */
?>

<?php while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
  
  <?php $args = array(
		'redirect'       => site_url( '/resident-portal/ ' )
	); ?>
	
	<?php wp_login_form( $args ); ?> 
	
	<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" title="Lost Password">Lost Your Password?</a>
	
<?php endwhile; ?>