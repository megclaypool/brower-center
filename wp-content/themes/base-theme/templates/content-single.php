<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div class="entry-content">
      <header>
	  <?php if(get_post_type() == 'news') { ?>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php } ?>
      <?php if (get_the_post_thumbnail()) { ?>
      <div class="single-banner">
<!--
      <?php if ( has_post_thumbnail() && !is_handheld() ) {
              echo  get_the_post_thumbnail( $post->ID, 'single-banner' ); 
            } ?>
      </div>
-->
      <?php } ?>
      <?php //get_template_part('templates/entry-meta'); ?>
    </header>
      <?php if(get_post_type() == 'news') { ?>
        <div class="author"><time class="updated" datetime="<?= get_the_time('c'); ?>"><?= get_the_date(); ?></time>
        	<?php if (get_field('publication')) { ?>
        	   | Publication: <?php the_field('publication'); ?> 
                <?php if (get_field('author')) { 
                  echo ' | Author: '; 
                  the_field('author');
                  } 
        	     } ?>
        </div>
      <?php } ?>
    	<?php if (get_field('room_capacity')) { ?>
    	    <div class="author">Room Capacity: <?php the_field('room_capacity'); ?></div>
    	<?php } ?>
      <?php if (get_field('room_size')) { ?>
          <div class="author">Room Size: <?php the_field('room_size'); ?></div>
      <?php } ?>
    	<?php if (get_field('date')) { ?>
    	    <div class="author"><?php the_field('date'); ?></div>
    	<?php } ?>
      <?php the_content(); ?>
      <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>')); ?>
      </footer>
    </div>

    <?php //comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>