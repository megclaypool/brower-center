<?php

// Fix for the WordPress 3.0 "paged" bug.
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


$posttype = get_field( 'post_type' );
$cats = get_field('category', $term);
$orderby = get_field('order_by');
$sort = get_field('sort');

$args = array(
        'post_type' => $posttype,
        'posts_per_page' => 10,
        'category__in' => $cats,
        'orderby' => $orderby,
        'order' => $sort,
        'paged' => $paged,
      );

query_posts($args);


if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="row">
<article <?php post_class(); ?>>
  <?php if ( has_post_thumbnail() && !is_mobile() ) { ?>
  <div class="hidden-xs col-sm-4 col-lg-3">
	  <div class="thumbnail-container">
	    <div class="entry-image">
	      <?php if(get_post_type() != 'program') { ?>
        <a href="<?php the_permalink(); ?>">
	      <?php
	        the_post_thumbnail( 'listing-thumbnail' );
	      ?>
	    </a>
      <?php } else { ?> 
        <?php
          the_post_thumbnail( 'listing-thumbnail' );
        ?>
      <?php } ?>
	    <?php if(get_post_type() == 'exhibit' || get_post_type() == 'event_room') {
		    echo '<div><a class="btn btn-default" href="'.get_the_permalink().'">LEARN MORE</a></div>';
	    } elseif (get_post_type() == 'program') { ?>
	    	<?php if (get_field('eventbrite_link')) { ?>
	    	    <div>
              <a class="btn btn-default" target="_blank" href="<?php the_field('eventbrite_link'); ?>">
                <?php if(get_field('purchase_text') ) {
                  the_field('purchase_text');
                } else {
                  echo 'Purchase Tickets';
                } ?></a>
              </a>
          </div>
	    	<?php } ?>
	    <?php } ?>
	    </div><!--/.entry-image-->
	  </div><!--/.thumnail-container-->
  </div>
  <div class="col-xs-12 col-sm-8 col-lg-9">
    <?php } else { ?>
  <div class="col-xs-12">
	<?php } ?>
	

  <div class="inner-container">
	<?php if(get_post_type() == 'program') { ?>
    	<?php if (get_field('eventbrite_link')) { ?>
    		<h2 class="entry-title"><a href="<?php the_field('eventbrite_link'); ?>" target="_blank"><?php the_title(); ?></a></h2>
    	<?php } ?>
    	
	<?php } else { ?>

    	<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    
    <?php } ?>  
	
    <?php get_template_part('templates/entry-meta'); ?>
    <div class="entry-summary">
      
      <?php if(get_post_type() == 'program') { ?>
      	<?php the_content(); ?>
      	<?php if (get_field('date')) { 
	    $date = DateTime::createFromFormat('Ymd', get_field('date'));
		?>
		<div class="byline author vcard"><?php echo $date->format('F j, Y');  ?> / <?php if (get_field('time')) { ?>
	  			<?php the_field('time'); ?>
	  		<?php } ?></div>
	  	<?php } ?>
	  <?php } else { ?>
	  	<?php echo excerpt(50); ?>
	  <?php } ?> 
    </div>
    
    <?php if(get_post_type() == 'exhibit' || get_post_type() == 'program' || get_post_type() == 'event_room') { //leave the space empty ?>
	
  <?php } elseif(get_post_type() == 'news') { ?>
      
      <div><a class="btn btn-default news-btn" href="<?php the_permalink(); ?>">MORE</a></div>
  
  <?php } else { ?>

    	<div><a class="btn btn-default" href="<?php the_permalink(); ?>">LEARN MORE</a></div>
    
    <?php } ?>


    </div>
  </div>
</article>
</div> <!-- end row -->
  <div class="clearfix"></div>

  <?php endwhile; else : ?>
    <div class="no-results"><p>There are currently no posts in this section</p></div>
  <?php endif; ?>

  <?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="pagination-container">
  <nav class="post-nav pull-right">
    <?php
      $args = array(
        'mid_size'           => 3,
        'next_text'          => __('More &raquo;'),
      );
      echo paginate_links( $args );
    ?>
  </nav>
</div>
<?php endif; ?>
<?php wp_reset_query(); ?>


<div class="clearfix"></div>
