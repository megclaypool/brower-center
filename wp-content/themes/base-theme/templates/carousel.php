<section class="carousel-wrapper">
	<div class="section-title">
		<h2>Testimonials</h2>
	</div>
	<div class="carousel">
		<ul class="slides">
<?php

// Fix for the WordPress 3.0 "paged" bug.
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
          'post_type' => 'foo-bar', //Add post type to query
          'paged' => $paged
        );

query_posts($args);


if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li class="item">
				<div class="item-text">
					<span class="quote-mark"></span>
					<?php the_content(); ?>
				</div>
				<?php if( get_field('quoted') ) : ?>
				<div class="quoted">
					<?php the_field('quoted'); ?>
				</div>
				<?php endif; ?>
			</li>
  <?php endwhile; else : ?>
    		<li class="no-results">There are currently no posts in this section</li>
  <?php endif; ?>
  		</ul>
	</div>
</section>