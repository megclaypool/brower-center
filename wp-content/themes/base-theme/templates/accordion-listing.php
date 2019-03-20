<div class="accordion-group" id="accordion1" role="tablist" aria-multiselectable="true">
<?php

// Fix for the WordPress 3.0 "paged" bug.
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


$posttype = get_field( 'post_type' );
$catslug = get_field('category');

$args = array(
          'post_type' => $posttype, //Add post type to query
          'posts_per_page' => 10,
          'category_name' => $catslug,
          'paged' => $paged
        );

query_posts($args);


if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="panel panel-default">
    	<div class="panel-heading" role="tab" id="heading<?php the_ID();?>">
			<h2 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion1" class="collapsed" href="#collapse<?php the_ID();?>" aria-expanded="false" aria-controls="collapse<?php the_ID();?>">
					<?php the_title();?>
				</a>
			</h2>
		
		</div>
		<div id="collapse<?php the_ID();?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php the_ID();?>">
		    <div class="panel-body">
				<?php the_content(); ?>
			</div><!--/.panel-body-->
		</div><!-- /.teaser-content-->
	</div>
	<div class="clearfix"></div>
	<?php endwhile; else : ?>
		<div class="no-results"><p>There are currently no opportunities in this section</p></div>
	<?php endif; ?>
<?php wp_reset_query(); ?>
</div>

<div class="fix"></div>